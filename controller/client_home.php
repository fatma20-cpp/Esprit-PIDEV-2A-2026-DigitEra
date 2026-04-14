<?php
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/session.php';

requireNotAdmin();

if (isset($_GET['logout'])) logout();

$currentUser = currentUser();

$initials = strtoupper(substr($currentUser['prenom'],0,1).substr($currentUser['nom'],0,1));
$fullName  = htmlspecialchars($currentUser['prenom'].' '.$currentUser['nom']);

$roleBadge = [
    'admin'       => ['color'=>'#38BDF8','bg'=>'rgba(56,189,248,.12)'],
    'client'      => ['color'=>'#22C55E','bg'=>'rgba(34,197,94,.12)'],
    'prestataire' => ['color'=>'#F59E0B','bg'=>'rgba(245,158,11,.12)'],
];
$badge = $roleBadge[$currentUser['role']] ?? $roleBadge['client'];

$html = file_get_contents(__DIR__ . '/../view/client_home.html');
$html = str_replace('__INITIALS__',   $initials,  $html);
$html = str_replace('__FULLNAME__',   $fullName,  $html);
$html = str_replace('__FIRSTNAME__',  htmlspecialchars($currentUser['prenom']), $html);
$html = str_replace('__ROLE__',       ucfirst(htmlspecialchars($currentUser['role'])), $html);
$html = str_replace('__ROLE_COLOR__', $badge['color'], $html);
$html = str_replace('__ROLE_BG__',    $badge['bg'],    $html);
echo $html;