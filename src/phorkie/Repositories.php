<?php
namespace phorkie;

class Repositories
{
    public function __construct()
    {
        $this->workDir = $GLOBALS['phorkie']['cfg']['workdir'];
        $this->gitDir  = $GLOBALS['phorkie']['cfg']['gitdir'];
    }

    /**
     * @return Repository
     */
    public function createNew()
    {
        chdir($this->gitDir);
        $dirs = glob('*.git', GLOB_ONLYDIR);
        array_walk($dirs, function ($dir) { return substr($dir, 0, -4); });
        sort($dirs, SORT_NUMERIC);
        $n = end($dirs) + 1;

        chdir($this->workDir);
        $dir = $this->workDir . '/' . $n . '/';
        mkdir($dir, 0777);//FIXME
        $r = new Repository();
        $r->id = $n;
        $r->workDir = $dir;
        $r->gitDir = $this->gitDir . '/' . $n . '.git/';
        mkdir($r->gitDir, 0777);//FIXME

        return $r;
    }

    /**
     * Get a list of repository objects
     *
     * @param integer $page    Page number, beginning with 0
     * @param integer $perPage Number of repositories per page
     *
     * @return array Array of Repositories
     */
    public function getList($page = 0, $perPage = 10)
    {
        chdir($this->gitDir);
        $dirs = glob('*.git', GLOB_ONLYDIR);
        sort($dirs, SORT_NUMERIC);

        $some = array_slice($dirs, $page * $perPage, $perPage);
        $repos = array();
        foreach ($some as $oneDir) {
            $r = new Repository();
            $r->loadById(substr($oneDir, 0, -4));
            $repos[] = $r;
        }
        return $repos;
    }
}

?>
