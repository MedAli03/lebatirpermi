<?php

namespace J4xdemos\Component\Batirpermi\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Database\ParameterType;

class CategorieModel extends ItemModel
{
	protected $_context = 'com_batirpermi.categorie';

	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		$pk = (int) ($pk ?: $app->input->getInt('id'));
		$db = $this->getDatabase();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__batirpermi_categories'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $pk, ParameterType::INTEGER);
		$db->setQuery($query);
		$data = $db->loadObject();
		$this->_item[$pk] = $data;

		return $this->_item[$pk];
	}

	public function create(array $data): bool
	{
		$object = (object) $data;

		return $this->getDatabase()->insertObject('#__batirpermi_categories', $object);
	}

	public function update(int $id, array $data): bool
	{
		$object = (object) array_merge(['id' => $id], $data);

		return $this->getDatabase()->updateObject('#__batirpermi_categories', $object, 'id', true);
	}

	public function setState(int $id, int $state): bool
	{
		$object = (object) ['id' => $id, 'state' => $state];

		return $this->getDatabase()->updateObject('#__batirpermi_categories', $object, 'id', true);
	}

	public function deleteById(int $id): bool
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__batirpermi_categories'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER);
		$db->setQuery($query);
		$db->execute();

		return true;
	}
}
