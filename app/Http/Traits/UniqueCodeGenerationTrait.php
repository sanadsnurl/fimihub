<?php

namespace App\Http\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use App\Model\qbeez_wallet_transaction;

trait UniqueCodeGenerationTrait {

    public function checkTxnID($txn_id)
    {
        $qbeez_wallet_transaction = new qbeez_wallet_transaction();
        return $qbeez_wallet_transaction->checkTxnIdExists($txn_id);
    }

    public function generateUniqueTxnID()
    {
        $txn_id = mt_rand(1000000000, 9999999999);

        // call the same function if the txn ID exists already
        if ($this->checkTxnID($txn_id)) {
            return generateUniqueTxnID();
        }

        // otherwise, it's valid and can be used
        return 'QBZ'.$txn_id;
    }


}
