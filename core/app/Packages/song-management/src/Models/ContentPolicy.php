<?php
namespace SongManage\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPolicy extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'content_policies';

    public function policy()
    {
        return $this->hasOne('SongManage\Models\Policy', 'PolicyID', 'PolicyID');
    }
}
