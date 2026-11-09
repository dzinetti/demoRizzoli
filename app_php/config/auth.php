<?php
function redirect_to(string $location): void
{
    header("Location: " . $location);
    exit();
}

function require_login(): void
{
    if (empty($_SESSION["id_utente"])) {
        redirect_to("../actions/login.php");
    }
}

function current_user_role_name(PDO $conn): ?string
{
    if (empty($_SESSION["id_ruolo"])) {
        return null;
    }

    $stmt = $conn->prepare("SELECT nome FROM ruolo WHERE id_ruolo = ? LIMIT 1");
    $stmt->execute([$_SESSION["id_ruolo"]]);
    $row = $stmt->fetch();

    return $row["nome"] ?? null;
}

function current_user_is_admin(PDO $conn): bool
{
    return current_user_role_name($conn) === "admin";
}

function require_student_area(PDO $conn): void
{
    require_login();
    if (current_user_is_admin($conn)) {
        redirect_to("dashboard_admin.php");
    }
}

function require_admin_area(PDO $conn): void
{
    require_login();
    if (!current_user_is_admin($conn)) {
        redirect_to("dashboard.php");
    }
}