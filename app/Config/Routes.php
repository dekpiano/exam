<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Student Page Routes
$routes->get('/', 'ExamController::index');
$routes->get('/lobby', 'ExamController::lobby');
$routes->get('/exam', 'ExamController::exam');
$routes->get('/result', 'ExamController::result');
$routes->get('uploads/(:any)', 'AdminController::serveUpload/$1');

// Student API Routes
$routes->group('api', function($routes) {
    $routes->post('register', 'ExamController::register');
    $routes->post('lobby/sync', 'ExamController::syncLobby');
    $routes->get('exam/status', 'ExamController::status');
    $routes->post('exam/start', 'ExamController::startExam');
    $routes->post('exam/submit', 'ExamController::submitExam');
    $routes->post('exam/cheat', 'ExamController::submitCheat');
    $routes->post('exam/log-error', 'ExamController::logError');
    $routes->post('exam/ai-grade', 'AdminController::aiGrade');
});

// Teacher Page Routes
$routes->get('/teacher', 'AdminController::index');
$routes->get('/teacher/dashboard', 'AdminController::dashboard');
$routes->get('/teacher/questions', 'AdminController::questions');
$routes->get('/teacher/monitor', 'AdminController::monitor');
$routes->get('/teacher/results', 'AdminController::results');
$routes->get('/teacher/logs', 'AdminController::logs');
$routes->get('/teacher/settings', 'AdminController::settings');
$routes->get('/teacher/exam-settings', 'AdminController::examSettings');
$routes->get('/teacher/manual', 'AdminController::manual');

// Teacher API Routes
$routes->group('api/teacher', function($routes) {
    $routes->post('login', 'AdminController::login');
    $routes->post('google-login', 'AdminController::googleLogin');
    $routes->get('logout', 'AdminController::logout');
    $routes->post('logout', 'AdminController::logout');
    $routes->get('settings', 'AdminController::getSettings');
    $routes->post('settings', 'AdminController::saveSettings');
    $routes->get('exams', 'AdminController::getExams');
    $routes->post('exams/save', 'AdminController::saveExam');
    $routes->post('exams/duplicate', 'AdminController::duplicateExam');
    $routes->post('exams/update-status', 'AdminController::updateExamStatus');
    $routes->post('exams/update-policy', 'AdminController::updateJoinPolicy');
    $routes->post('exams/update-mode', 'AdminController::updateExamMode');
    $routes->post('exams/delete', 'AdminController::deleteExam');
    $routes->get('questions', 'AdminController::getQuestions');
    $routes->post('questions/save', 'AdminController::saveQuestion');
    $routes->post('questions/delete', 'AdminController::deleteQuestion');
    $routes->post('questions/clear', 'AdminController::clearAllQuestions');
    $routes->post('questions/update-points', 'AdminController::updateQuestionPoints');
    $routes->post('questions/import', 'AdminController::importQuestions');
    $routes->post('questions/upload-image', 'AdminController::uploadImage');
    $routes->post('questions/delete-image', 'AdminController::deleteImage');
    $routes->get('results', 'AdminController::getResults');
    $routes->get('results/delete', 'AdminController::deleteResult');
    $routes->post('results/delete', 'AdminController::deleteResult');
    $routes->post('results/grade', 'AdminController::gradeResults');
    $routes->post('results/update-score', 'AdminController::updateScore');
    $routes->post('results/update-score-direct', 'AdminController::updateScoreDirect');
    $routes->get('monitor', 'AdminController::getMonitor');
    $routes->post('monitor/delete', 'AdminController::deleteStudent');
    $routes->get('logs', 'AdminController::getLogs');
    $routes->post('logs/clear', 'AdminController::clearLogs');
});
