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

$t->is($file->isModified(), false, 'File not modified');

$file->setContent('Lipsum');

$t->is($file->getContent(), 'Lipsum', 'Not empty');

$t->is($file->isModified(), true, 'File modified');

$file->save('Saving FILE');

$t->is($file->isModified(), false, 'File not modified');

$t->is($file->isNew(), false, 'File exists');

$file2 = new Git\File($repo, 'README');

$file2->save('Add README');

var_dump($repo->getCommits());

$commits = $file->getCommits();

$t->is(count($commits), 1, 'One commit');

$t->is($commits[0]['message'], 'Saving FILE', 'Got the right commit');

$file->move('MOVED', 'Moving FILE');

$t->is($file->getFilename(), 'MOVED');

$t->is($file->isNew(), false);

$t->is(file_get_contents($repo->getDir().'/MOVED'), 'Lipsum');

$t->is(file_exists($repo->getDir().'/FILE'), false);

$commits = $file->getCommits();

$t->is(count($commits), 1, 'One commit');

$t->is($commits[0]['message'], 'Moving FILE', 'Got the right commit');

$file->delete('Deleting FILE');

$t->is(file_exists($repo->getDir().'/MOVED'), false);

var_dump($repo->getCommits());

exec('rm -rf '.$repo->getDir());
