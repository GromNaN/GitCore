<?php

require_once __DIR__.'/vendor/lime.php';
require_once __DIR__.'/../bootstrap.php';
require_once __DIR__.'/GitRepositoryTestHelper.php';

$t = new lime_test();

$repo = _createTmpGitRepo($t);

$file = new Git\File($repo, 'FILE');
$t->is(true, $file->isNew(), 'New file');
$t->is($file->getContent(), '', 'Empty');
$t->is($file->getFilename(), 'FILE', 'File name');
$t->is($file->isModified(), true, 'File created');

$file->setContent('Lipsum');
$t->is($file->getContent(), 'Lipsum', 'Not empty');
$t->is($file->isModified(), true, 'File modified');

$file->save('Saving FILE 21');
$t->is($file->isModified(), false, 'File not modified');
$t->is($file->isNew(), false, 'File exists');

$file2 = new Git\File($repo, 'README');
$file2->save('Add README 26');
$commits = $file2->getCommits();
$t->is(count($commits), 1, 'One commit');
$t->is($commits[0]->getMessage(), 'Add README 26', 'Got the right commit');

$file2->setContent('Random content');
$file2->save('Save README 32');
$commits = $file2->getCommits();
$t->is(count($commits), 2, 'Two commits');
$t->is($commits[0]->getMessage(), 'Save README 32', 'Got the right commit');
$t->is($commits[1]->getMessage(), 'Add README 26', 'Got the right commit');

$file->move('MOVED', 'Moving FILE 38');
$t->is($file->getFilename(), 'MOVED');
$t->is($file->isNew(), false);
$t->is(file_get_contents($repo->getDir().'/MOVED'), 'Lipsum');
$t->is(file_exists($repo->getDir().'/FILE'), false);

$commits = $file->getCommits();
$t->is(count($commits), 1, 'One commit');
$t->is($commits[0]->getMessage(), 'Moving FILE 38', 'Got the right commit');

$file->delete('Deleting FILE 48');
$t->is(file_exists($repo->getDir().'/MOVED'), false);

// Ouf, that's done.
