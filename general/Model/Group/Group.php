<?php


namespace General\Model\Group;


use Engine\DI\DI;
use Engine\AbstractModel;

/**
 * Class Group
 * @package Admin\Model
 */
class Group extends AbstractModel
{
    /**
     * @var int
     */
    public $user_group_id;



    /**
     * Group constructor.
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);

        self::setUserGroupId(1);
    }



    /**
     * @param int $user_group_id
     */
    public function setUserGroupId(int $user_group_id):void
    {
        $this->user_group_id = $user_group_id;
    }

}