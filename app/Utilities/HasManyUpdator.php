<?php

namespace App\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HasManyUpdater
{
    /** @var Model */
    private $owner;

    /** @var string */
    private $related;

    /** @var string|null */
    private $foreignKey;

    /** @var string|null */
    private $localKey;

    /** @var string|null */
    private $relation;

    /**
     * @param  Model  $owner Instance of the owning model
     * @param  string  $related Fully qualified path of the related model
     * @param  string|null  $foreignKey Foreign key of the owner on the related table
     * @param  string|null  $relation Name of the relation method on the owner model
     */
    public function __construct(
        Model $owner,
        string $related,
        ?string $foreignKey = null,
        ?string $relation = null
    ) {

        $this->owner = $owner;

        if (! $this->owner->exists) {
            throw (new ModelNotFoundException())
                ->setModel(get_class($this->owner));
        }

        $relatedInstance = App::make($related);

        if (! $relatedInstance instanceof Model) {
            throw new InvalidArgumentException(
                "Property 'related' should be an instance of ".Model::class
            );
        }

        $this->related = $relatedInstance;
        $this->relation = $relation ?? $this->related->getTable();

        if (! method_exists($this->owner, $this->relation)) {
            throw RelationNotFoundException::make($this->owner, $this->relation);
        }

        $this->foreignKey = $foreignKey ?? Str::singular($this->owner->getTable()).'_id';
    }

    public function update(array $updatedRecords, bool $attachWorkerType = false) : array
    {
        $changes = DB::transaction(function () use ($attachWorkerType, $updatedRecords) {
            $createdIds = [];
            $updatedIds = [];
            $existingIds = $this->owner->{$this->relation}()->pluck('id')->toArray();

            foreach ($updatedRecords as $updatedRecord) {
                $id = data_get($updatedRecord, 'id');

                if (is_numeric($id)) {
                    $model = $this->related->findOrFail($id);

                    if ($attachWorkerType) {
                        $this->syncWorkerTypes($model, $updatedRecord);
                    }
                    $updatedIds[] = $id;
                } else {
                    $relation = $this->relation;
                    $createdId = $createdIds[] = $this->owner->$relation()->create($updatedRecord)->id;

                    $model = $this->related::find($createdId);

                    if ($attachWorkerType) {
                        $this->syncWorkerTypes($model, $updatedRecord);
                    }
                }
            }

            $deletedIds = array_diff($existingIds, $updatedIds);

            if (! empty($deletedIds)) {
                $this->owner
                    ->{$this->relation}()
                    ->whereIn($this->related->getTable().'.id', $deletedIds)
                    ->delete();
            }

            return [
                'created' => $createdIds,
                'deleted' => $deletedIds,
                'updated' => $updatedIds
            ];
        });

        return $changes;
    }

    /**
     * @param $model
     * @param $updatedRecord
     */
    public function syncWorkerTypes($model, $updatedRecord): void
    {
        $model->workerTypes()->detach();
        foreach ($updatedRecord['worker_types'] as $workerType) {
            $model->workerTypes()->attach($workerType['id'], ['quantity' => $workerType['quantity']]);
        }
    }
}
