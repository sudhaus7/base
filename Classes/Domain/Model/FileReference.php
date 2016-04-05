<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 05/04/16
 * Time: 14:46
 */

namespace SUDHAUS7\Sudhaus7Base\Domain\Model;


class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{


    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $alternative;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $tablenames = 'tt_content';

    /**
     * @var string
     */
    protected $fieldname = 'assets';

    /**
     * TableLocal
     *
     * @var \string
     */
    protected $tableLocal = 'sys_file';

    /**
     * UidForeign
     *
     * @var \int
     */
    protected $uidForeign;
    /**
     * SortingForeign
     *
     * @var \int
     */
    protected $sortingForeign = 1;
    /**
     * Sorting
     *
     * @var int
     */
    protected $sorting = 1;
    /**
     * CruserId
     *
     * @var int
     */
    protected $cruserId;

    /**
     * @param \TYPO3\CMS\Core\Resource\File $file
     */
    public function setFile(\TYPO3\CMS\Core\Resource\File $file)
    {
        $properties = $file->getProperties();
        $this->uidLocal = (int)$properties['uid'];
    }

    /**
     * @param int $uid
     */
    public function setUidLocal($uid)
    {
        $this->uidLocal = $uid;
    }

    /**
     * @return int
     */
    public function getUidLocal()
    {
        return $this->uidLocal;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAlternative()
    {
        return $this->alternative;
    }

    /**
     * @param string $alternative
     */
    public function setAlternative($alternative)
    {
        $this->alternative = $alternative;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the Tablenames
     *
     * @return \string $tablenames
     */
    public function getTablenames()
    {
        return $this->tablenames;
    }

    /**
     * Sets the Tablenames
     *
     * @param \string $tablenames
     * @return void
     */
    public function setTablenames($tablenames)
    {
        $this->tablenames = $tablenames;
    }

    /**
     * Returns the Fieldname
     *
     * @return \string $fieldname
     */
    public function getFieldname()
    {
        return $this->fieldname;
    }

    /**
     * Sets the Fieldname
     *
     * @param \string $fieldname
     * @return void
     */
    public function setFieldname($fieldname)
    {
        $this->fieldname = $fieldname;
    }

    /**
     * Returns the UidForeign
     *
     * @return \int $uidForeign
     */
    public function getUidForeign()
    {
        return $this->uidForeign;
    }

    /**
     * Sets the UidForeign
     *
     * @param int $uidForeign
     * @return void
     */
    public function setUidForeign($uidForeign)
    {
        $this->uidForeign = $uidForeign;
    }

    /**
     * Returns the TableLocal
     *
     * @return \string $tableLocal
     */
    public function getTableLocal()
    {
        return $this->tableLocal;
    }

    /**
     * Sets the TableLocal
     *
     * @param \string $tableLocal
     * @return void
     */
    public function setTableLocal($tableLocal)
    {
        $this->tableLocal = $tableLocal;
    }

    /**
     * Returns the SortingForeign
     *
     * @return \int $sortingForeign
     */
    public function getSortingForeign()
    {
        return $this->sortingForeign;
    }

    /**
     * Sets the SortingForeign
     *
     * @param \int $sortingForeign
     * @return void
     */
    public function setSortingForeign($sortingForeign)
    {
        $this->sortingForeign = $sortingForeign;

    }

    /**
     * Returns the Sorting
     *
     * @return int $sorting
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Sets the Sorting
     *
     * @param \string $sorting
     * @return void
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * Returns the CruserId
     *
     * @return int $cruserId
     */
    public function getCruserId()
    {
        return $this->cruserId;
    }

    /**
     * Sets the CruserId
     *
     * @param int $cruserId
     * @return void
     */
    public function setCruserId($cruserId)
    {
        $this->cruserId = $cruserId;
    }

}
