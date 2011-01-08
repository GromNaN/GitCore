<?php

/**
 *
 * @param lime_test $t
 * @return Git\Repository the git repo
 */
function _createTmpGitRepo(lime_test $t, array $options = array())
{
    // Delete old test repos
    exec('rm -rf '.sys_get_temp_dir().'/php-git-repo/*');
    
    $repoDir = sys_get_temp_dir().'/php-git-repo/'.uniqid();
    $t->ok(!is_dir($repoDir.'/.git'), $repoDir.' is not a Git repo');

    try {
        new Git\Repository($repoDir, true, $options);
        $t->fail($repoDir.' is not a valid git repository');
    } catch (InvalidArgumentException $e) {
        $t->pass($repoDir.' is not a valid git repository');
    }

    $t->comment('Create Git repo');
    exec('git init '.escapeshellarg($repoDir));
    $t->ok(is_dir($repoDir.'/.git'), $repoDir.' is a Git repo');

    $repo = new Git\Repository($repoDir, true, $options);
    $t->isa_ok($repo, 'Git\Repository', $repoDir.' is a valid git repo');

    $originalRepoDir = dirname(__FILE__).'/repo';
    foreach (array('README.markdown', 'index.php') as $file) {
        copy($originalRepoDir.'/'.$file, $repoDir.'/'.$file);
    }

    return $repo;
}