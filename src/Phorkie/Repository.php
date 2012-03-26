<?php
namespace Phorkie;


class Repository
{
    /**
     * Repository ID (number in repositories directory)
     *
     * @var integer
     */
    public $id;

    /**
     * Full path to the git repository
     *
     * @var string
     */
    public $repoDir;

    /**
     * Load Repository data from GET-Request
     *
     * @return void
     *
     * @throws Exception When something is wrong
     */
    public function loadFromRequest()
    {
        if (!isset($_GET['id'])) {
            throw new Exception_Input('Paste ID missing');
        }
        if (!is_numeric($_GET['id'])) {
            throw new Exception_Input('Paste ID not numeric');
        }
        $this->id = (int)$_GET['id'];

        $repoDir = $GLOBALS['phorkie']['cfg']['repos'] . '/' . $this->id;
        if (!is_dir($repoDir)) {
            throw new Exception_NotFound('Paste not found');
        }
        $this->repoDir = $repoDir;
    }

    /**
     * Loads the list of files in this repository
     *
     * @return File[] Array of files
     */
    public function getFiles()
    {
        $files = glob($this->repoDir . '/*');
        $arFiles = array();
        foreach ($files as $path) {
            $arFiles[] = new File($path, $this);
        }
        return $arFiles;
    }

    public function getDescription()
    {
        return file_get_contents($this->repoDir . '/.git/description');
    }

    /**
     * Get a link to the repository
     *
     * @param string $type Link type. Supported are:
     *                     - "edit"
     *                     - "display"
     *
     * @return string
     */
    public function getLink($type)
    {
        if ($type == 'edit') {
            return '/' . $this->id . '/edit';
        } else if ($type == 'display') {
            return '/' . $this->id;
        }
        throw new Exception('Unknown type');
    }

}

?>
