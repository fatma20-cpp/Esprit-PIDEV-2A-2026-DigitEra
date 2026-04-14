<?php
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/session.php';

requireRole('admin');

if (isset($_GET['logout'])) logout();

$currentUser = currentUser();

// Admin: handle delete
if (isset($_GET['delete'])) {
    $model = new UserModel();
    $model->deleteUser((int)$_GET['delete']);
    header('Location: index.php?page=dashboard');
    exit;
}

$model  = new UserModel();
$users  = $model->getAllUsers();
$total  = count($users);
$clients= count(array_filter($users, fn($u) => $u['role'] === 'client'));
$presta = count(array_filter($users, fn($u) => $u['role'] === 'prestataire'));
$admins = count(array_filter($users, fn($u) => $u['role'] === 'admin'));

$hour     = (int)date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
$initials = strtoupper(substr($currentUser['prenom'],0,1).substr($currentUser['nom'],0,1));
$fullName = htmlspecialchars($currentUser['prenom'].' '.$currentUser['nom']);

$roleBadge = [
    'admin'       => ['color'=>'#38BDF8','bg'=>'rgba(56,189,248,.12)'],
    'client'      => ['color'=>'#22C55E','bg'=>'rgba(34,197,94,.12)'],
    'prestataire' => ['color'=>'#F59E0B','bg'=>'rgba(245,158,11,.12)'],
];

// Build rows HTML
$rowsHtml = '';
if (empty($users)) {
    $rowsHtml = '<tr><td colspan="7" class="empty-cell">No users found.</td></tr>';
} else {
    foreach ($users as $u) {
        if ($u['role'] === 'admin') continue;
        $b     = $roleBadge[$u['role']] ?? $roleBadge['client'];
        $uInit = strtoupper(substr($u['prenom'],0,1).substr($u['nom'],0,1));
        $date  = date('M d, Y', strtotime($u['date_inscription']));
        $uName = htmlspecialchars($u['prenom'].' '.$u['nom']);
        $uEmail= htmlspecialchars($u['email']);
        $uPhone= $u['telephone'] ? htmlspecialchars($u['telephone']) : '<span class="no-data">—</span>';
        $action= ($u['id_user'] != $currentUser['id'])
            ? '<a href="index.php?page=dashboard&delete='.$u['id_user'].'" class="btn-delete" onclick="return confirm(\'Delete '.$uName.'?\')">
                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                 Delete</a>'
            : '<span class="self-label">You</span>';

        $rowsHtml .= "<tr>
            <td class=\"td-id\">{$u['id_user']}</td>
            <td><div class=\"user-row\"><div class=\"user-avatar-sm\">$uInit</div><span class=\"user-fullname\">$uName</span></div></td>
            <td class=\"td-muted\">$uEmail</td>
            <td><span class=\"role-badge\" style=\"color:{$b['color']};background:{$b['bg']}\">" . ucfirst($u['role']) . "</span></td>
            <td class=\"td-muted\">$uPhone</td>
            <td class=\"td-muted td-date\">$date</td>
            <td>$action</td>
        </tr>";
    }
}

$html = file_get_contents(__DIR__ . '/../view/dashboard.html');
$html = str_replace('__GREETING__',   $greeting,  $html);
$html = str_replace('__FIRSTNAME__',  htmlspecialchars($currentUser['prenom']), $html);
$html = str_replace('__FULLNAME__',   $fullName,  $html);
$html = str_replace('__INITIALS__',   $initials,  $html);
$html = str_replace('__TOTAL__',      $total,     $html);
$html = str_replace('__ADMINS__',     $admins,    $html);
$html = str_replace('__CLIENTS__',    $clients,   $html);
$html = str_replace('__PRESTA__',     $presta,    $html);
$html = str_replace('__TABLE_ROWS__', $rowsHtml,  $html);
echo $html;