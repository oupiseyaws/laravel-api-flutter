<?php
namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionRepository
{
    /**
     * @var Transaction
     */
    protected Transaction $transaction;

    /**
     * Transaction constructor.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get all transaction.
     *
     * @return Transaction $transaction
     */
    public function all(Request $request)
    {
        $display = $request->get('display');

        $data =  QueryBuilder::for(Transaction::class)
        ->allowedFilters('description')
        ->defaultSort('-created_at');

        if($display){
            return $data->paginate($display)
            ->appends(request()->query());
        }

        return $data->get();
    }

    /**
     * Get transaction by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->transaction->find($id);
    }

    /**
     * Save Transaction
     *
     * @param $data
     * @return Transaction
     */
     public function save(array $data)
    {
        return Transaction::create($data);
    }

     /**
     * Update Transaction
     *
     * @param $data
     * @return Transaction
     */
    public function update(array $data, int $id)
    {
        $transaction = $this->transaction->find($id);
        $transaction->update($data);
        return $transaction;
    }

    /**
     * Delete Transaction
     *
     * @param $data
     * @return Transaction
     */
   	 public function delete(int $id)
    {
        $transaction = $this->transaction->find($id);
        $transaction->delete();
        return $transaction;
    }
}
