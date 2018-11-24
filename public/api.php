<?php
declare(strict_types=1);

require_once __DIR__ . '/../autoloader.php';

session_start();
if (empty($_SESSION['user'])) {
    header('HTTP/1.0 401 Authentication required');
}

$body = file_get_contents('php://input');
if (empty($body)) {
    header('HTTP/1.0 400 Bad request');
}

$request = @json_decode($body, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    header('HTTP/1.0 400 Bad request');
}

if (!isset($request['action'])) {
    header('HTTP/1.0 400 Bad request');
}

switch ($request['action']) {
    case 'ping':
        echo '{"success":true,"data":"pong"}';
        break;
    case 'rotate':
        $reward = $container->getUserRewardService()->getCurrentReward();
        $rotated = false;
        if (null === $reward) {
            $reward = $container->getRewardService()->rotate();
            $rotated = true;
        }
        echo json_encode([
            'success' => true,
            'data' => $reward->getData(),
            'rotated' => $rotated,
        ]);
        break;
    case 'claim':
        $reward = $container->getUserRewardService()->getCurrentReward();
        if (null !== $reward) {
            $container->getRewardService()->claim($reward);
            $container->getUserRewardService()->removeReward($reward);
            echo '{"success":true}';
        } else {
            echo json_encode(['success' => false, 'error' => 'Reward not exists']);
        }
        break;
    case 'decline':
        $reward = $container->getUserRewardService()->getCurrentReward();
        if (null !== $reward) {
            $container->getRewardService()->decline($reward);
            $container->getUserRewardService()->removeReward($reward);
            echo '{"success":true}';
        } else {
            echo json_encode(['success' => false, 'error' => 'Reward not exists']);
        }
        return json_encode(['success' => $success]);
        break;
    default:
        echo '{"success":false,"error":"Unknown requested action"}';
        break;
}
