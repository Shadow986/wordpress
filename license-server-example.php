<?php
/**
 * Simple License Server Example
 * Host this on your domain to handle license validation
 */

header('Content-Type: application/json');

// Your license keys database (use proper database in production)
$valid_licenses = [
    'NE-2024-ABCD-1234' => [
        'expires' => '2025-12-31',
        'sites' => ['example.com', 'test.com'],
        'active' => true
    ],
    'NE-2024-EFGH-5678' => [
        'expires' => '2025-12-31', 
        'sites' => ['another-site.com'],
        'active' => true
    ]
];

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$license_key = $_POST['license_key'] ?? '';
$site_url = $_POST['site_url'] ?? '';

switch ($action) {
    case 'activate':
        if (!isset($valid_licenses[$license_key])) {
            echo json_encode(['success' => false, 'message' => 'Invalid license key']);
            exit;
        }
        
        $license = $valid_licenses[$license_key];
        
        if (!$license['active']) {
            echo json_encode(['success' => false, 'message' => 'License deactivated']);
            exit;
        }
        
        if (strtotime($license['expires']) < time()) {
            echo json_encode(['success' => false, 'message' => 'License expired']);
            exit;
        }
        
        $domain = parse_url($site_url, PHP_URL_HOST);
        if (!in_array($domain, $license['sites'])) {
            // Add site to license (or check limit)
            $license['sites'][] = $domain;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'License activated',
            'expires' => $license['expires']
        ]);
        break;
        
    case 'deactivate':
        echo json_encode(['success' => true, 'message' => 'License deactivated']);
        break;
        
    case 'check':
        if (!isset($valid_licenses[$license_key])) {
            echo json_encode(['success' => false, 'message' => 'Invalid license']);
            exit;
        }
        
        $license = $valid_licenses[$license_key];
        echo json_encode([
            'success' => true,
            'active' => $license['active'],
            'expires' => $license['expires']
        ]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
