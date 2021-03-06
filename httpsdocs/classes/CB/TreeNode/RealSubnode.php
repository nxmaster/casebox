<?php
namespace CB\TreeNode;

use CB\DB;

class RealSubnode extends Base
{
    protected function acceptedPath()
    {
        $p = &$this->path;

        if (((empty($this->config['pid']) || (@$this->config['pid'] == '0')) && empty($this->lastNode)) ||
            (!empty($this->lastNode) && (@$this->config['pid'] == $this->lastNode->id))
        ) {
            return true;
        }

        return false;
    }

    public function getChildren(&$pathArray, $requestParams)
    {
        $rez = array();
        $this->path = $pathArray;
        $this->lastNode = @$pathArray[sizeof($pathArray) - 1];
        $this->requestParams = $requestParams;

        if (!$this->acceptedPath()) {
            return;
        }
        /* should start with path check and see if child request is for a real db node*/

        $rez = array(
            'data' => array(
                array(
                    'name' => $this->config['title']
                    ,'id' => $this->config['realNodeId']
                    ,'iconCls' => 'icon-folder'
                    ,'has_childs' => true
                )
            )
        );

        return $rez;
    }

    public function getName($id = false)
    {
        return $this->config['title'];
    }
}
