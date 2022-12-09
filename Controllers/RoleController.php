<?php

$role_id = 0;
$name = "";
$description = "";
$isEditting = false;
$roles = array();
$errors = array();

// Get all roles
function getRoles()
{
    global $dataBaseConnection;
    $sqlQuery = "SELECT * FROM roles";
    $result = mysqli_query($dataBaseConnection, $sqlQuery);

    $roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $roles;
}

function getPermissions()
{
    global $dataBaseConnection;
    $sqlQuery = "SELECT * FROM permissions";
    $result = mysqli_query($dataBaseConnection, $sqlQuery);

    $permissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $permissions;
}

// Store a new role to database
function store()
{
    global $dataBaseConnection, $errors, $name, $description;

    $errors = dataValidator($_POST, ['save_role']);
    if (count($errors) === 0) {
        // receive form values
        $name = esc($_POST['name']);
        if (isset($_POST['published'])) {
            $published = "true";
        }

        $sqlQuery = "INSERT INTO roles(name, description) VALUES ('$name', '$description')";
        if (mysqli_query($dataBaseConnection, $sqlQuery)) {
            $_SESSION['success_msg'] = "Role created successfully";
            header("location: " . BASE_URL . "admin/roles/roleList.php");
        } else {
            $_SESSION['error_msg'] = "Something went wrong. Could not save role in Database";
        }
    }
}

// Update a specific role
function update($role_id)
{
    // pull in global form variables into function
    global $dataBaseConnection, $errors, $name, $isEditting;
    // validate form
    $errors = dataValidator($_POST, ['update_role']);

    if (count($errors) === 0) {
        // receive form values
        $name = esc($_POST['name']);
        $description = esc($_POST['description']);

        $sqlQuery = "UPDATE roles SET name='$name', description='$description' WHERE id=$role_id";

        if (mysqli_query($dataBaseConnection, $sqlQuery)) {
            $_SESSION['success_msg'] = "Role successfully updated";
            $isEditting = false;
            header("location: " . BASE_URL . "admin/roles/roleList.php");
        } else {
            $_SESSION['error_msg'] = "Something went wrong. Could not save role in Database";
        }
    }
}

function edit($role_id)
{
    global $dataBaseConnection, $name, $description, $isEditting;
    $sqlQuery = "SELECT * FROM roles WHERE id=$role_id";
    $result = mysqli_query($dataBaseConnection, $sqlQuery);
    $role = mysqli_fetch_assoc($result);

    $role_id = $role['id'];
    $name = $role['name'];
    $description = $role['description'];
    $isEditting = true;
}

// Get permissions to a particular role
function getRoleAllPermissions($role_id)
{
    global $dataBaseConnection;
    $sqlQuery = "SELECT * FROM permissions WHERE id=(SELECT permission_id FROM permission_role WHERE role_id=$role_id)";
    $result = mysqli_query($dataBaseConnection, $sqlQuery);

    $r_permissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $r_permissions;
}

// Save permissions to a specific role
function saveRolePermissions($permission_ids, $role_id)
{
    global $dataBaseConnection;

    $sqlQuery = "DELETE FROM permission_role WHERE role_id=$role_id";
    mysqli_query($dataBaseConnection, $sqlQuery);

    foreach ($permission_ids as $id) {
        $sqlQuery_2 = "INSERT INTO permission_role (role_id, permission_id) VALUES ($role_id, $id)";
        mysqli_query($dataBaseConnection, $sqlQuery_2);
    }

    $_SESSION['success_msg'] = "Permissions saved";
    header("location: roleList.php");
}

function deleteRole($role_id) {
    global $dataBaseConnection;
    $sqlQuery = "DELETE FROM roles WHERE id=$role_id";
    mysqli_query($dataBaseConnection, $sqlQuery);

    $_SESSION['success_msg'] = "Role trashed!!";
    header("location: " . BASE_URL . "admin/roles/roleList.php");
  }

function dataValidator($role, $ignoreFields)
{
    global $dataBaseConnection;
    $errors = [];

    foreach ($role as $key => $value) {
        if (in_array($key, $ignoreFields)) {
            continue;
        }
        if (empty($role[$key])) {
            $errors[$key] = "This field is required";
        }
    }

    return $errors;
}

// escape value from form
function esc(String $value)
{
    // bring the global db connect object into function
    global $dataBaseConnection;

    $val = trim($value); // remove empty space sorrounding string
    $val = mysqli_real_escape_string($dataBaseConnection, $value);

    return $val;
}

// ACTION: update role
if (isset($_POST['update_role'])) {
    $role_id = $_POST['role_id'];
    update($role_id);
}
// ACTION: Save Role
if (isset($_POST['save_role'])) {
    store();
}
// ACTION: fetch role for editting
if (isset($_GET["edit_role"])) {
    $role_id = $_GET['edit_role'];
    edit($role_id);
}

if (isset($_POST['save_permissions'])) {
    $permission_ids = $_POST['permission'];
    $role_id = $_POST['role_id'];
    saveRolePermissions($permission_ids, $role_id);
}

// ACTION: Delete role
if (isset($_GET['delete_role'])) {
    $role_id = $_GET['delete_role'];
    deleteRole($role_id);
}
