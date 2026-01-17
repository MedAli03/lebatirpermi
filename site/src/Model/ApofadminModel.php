<?php

namespace J4xdemos\Component\Batirpermi\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Database\ParameterType;

class ApofadminModel extends ItemModel
{
	protected $_context = 'com_batirpermi.apofadmin';

	public function __construct($config = [])
	{
		$app = Factory::getApplication();
		if ($app->input->get('id')) {
			$db = $this->getDatabase();
			$query = $db->getQuery(true)
				->update($db->quoteName('#__batirpermi_suivies'))
				->set($db->quoteName('downloaded') . ' = (' . $db->quoteName('downloaded') . ' + 1)')
				->where($db->quoteName('id') . ' = :id')
				->bind(':id', $app->input->getInt('id'), ParameterType::INTEGER);
			$db->setQuery($query);
			$db->execute();
		}
	}

	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		$pk = (int) ($pk ?: $app->input->getInt('id'));
		$db = $this->getDatabase();
		$query = $db->getQuery(true)
			->select(['a.*', 'b.title as lacategorie'])
			->from($db->quoteName('#__batirpermi_suivies', 'a'))
			->leftJoin($db->quoteName('#__batirpermi_categories', 'b') . ' ON a.lacated = b.id')
			->where($db->quoteName('a.id') . ' = :id')
			->bind(':id', $pk, ParameterType::INTEGER);
		$db->setQuery($query);
		$data = $db->loadObject();
		$this->_item[$pk] = $data;

		return $this->_item[$pk];
	}

	public function create(array $data): bool
	{
		$object = (object) $data;

		return $this->getDatabase()->insertObject('#__batirpermi_suivies', $object);
	}

	public function update(int $id, array $data): bool
	{
		$object = (object) array_merge(['id' => $id], $data);

		return $this->getDatabase()->updateObject('#__batirpermi_suivies', $object, 'id', true);
	}

	public function setState(int $id, int $state): bool
	{
		$object = (object) ['id' => $id, 'state' => $state];

		return $this->getDatabase()->updateObject('#__batirpermi_suivies', $object, 'id', true);
	}

	public function deleteById(int $id): bool
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__batirpermi_suivies'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER);
		$db->setQuery($query);
		$db->execute();

		return true;
	}

	public function getImageJson(int $id): ?string
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true)
			->select($db->quoteName('image'))
			->from($db->quoteName('#__batirpermi_suivies'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER);
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result ?: null;
	}

	public function clearImage(int $id): bool
	{
		$object = (object) ['id' => $id, 'image' => ''];

		return $this->getDatabase()->updateObject('#__batirpermi_suivies', $object, 'id', true);
	}
}
