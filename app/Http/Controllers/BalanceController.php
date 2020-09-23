<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBalanceRequest;
use App\Http\Requests\UpdateBalanceRequest;
use App\Repositories\BalanceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class BalanceController extends AppBaseController
{
    /** @var  BalanceRepository */
    private $balanceRepository;

    public function __construct(BalanceRepository $balanceRepo)
    {
        $this->balanceRepository = $balanceRepo;
    }

    /**
     * Display a listing of the Balance.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->balanceRepository->pushCriteria(new RequestCriteria($request));
        $balances = $this->balanceRepository->all();
        $balanceTotal = 0;
        $balancesDay=[];
        $balancesMonth=[];
        $balancesType = [];
        $weeklyBalance = [];
        foreach($balances as $balance){
            $balanceTotal+=$balance->amount;

            if(empty($balancesDay[date('Y-m-d',strtotime($balance->created_at))])){
                $balancesDay[date('Y-m-d',strtotime($balance->created_at))] = 0;
            }
            $balancesDay[date('Y-m-d',strtotime($balance->created_at))]+=$balance->amount;

            if(empty($balancesMonth[date('Y-m',strtotime($balance->created_at))])){
                $balancesMonth[date('Y-m',strtotime($balance->created_at))] = 0;
            }
            $balancesMonth[date('Y-m',strtotime($balance->created_at))]+=$balance->amount;

            if(empty($balancesType[$balance->type])){
                $balancesType[$balance->type] = 0;
            }
            $balancesType[$balance->type]+=$balance->amount;

            $week = date('W', strtotime($balance->created_at)); // note that ISO weeks start on Monday
            $firstWeekOfMonth = date('W', strtotime(date('Y-m-01', strtotime($balance->created_at))));
            if(empty($weeklyBalance[($week < $firstWeekOfMonth ? $week : $week - $firstWeekOfMonth)])){
                $weeklyBalance[($week < $firstWeekOfMonth ? $week : $week - $firstWeekOfMonth)] = 0;
            }
            $weeklyBalance[($week < $firstWeekOfMonth ? $week : $week - $firstWeekOfMonth)]+=$balance->amount;


        }
        asort($balancesType);
        return view('balances.index')
            ->with('balances', $balances)
            ->with('balanceTotal',$balanceTotal)
        ->with('balancesDay',$balancesDay)
            ->with('balancesMonth',$balancesMonth)
            ->with('balancesType',$balancesType)
        ->with('weeklyBalance',$weeklyBalance);
    }

    /**
     * Show the form for creating a new Balance.
     *
     * @return Response
     */
    public function create()
    {
        return view('balances.create');
    }

    /**
     * Store a newly created Balance in storage.
     *
     * @param CreateBalanceRequest $request
     *
     * @return Response
     */
    public function store(CreateBalanceRequest $request)
    {
        $input = $request->all();

        $balance = $this->balanceRepository->create($input);

        Flash::success('Balance saved successfully.');

        return redirect(route('balances.index'));
    }

    /**
     * Display the specified Balance.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $balance = $this->balanceRepository->findWithoutFail($id);

        if (empty($balance)) {
            Flash::error('Balance not found');

            return redirect(route('balances.index'));
        }

        return view('balances.show')->with('balance', $balance);
    }

    /**
     * Show the form for editing the specified Balance.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $balance = $this->balanceRepository->findWithoutFail($id);

        if (empty($balance)) {
            Flash::error('Balance not found');

            return redirect(route('balances.index'));
        }

        return view('balances.edit')->with('balance', $balance);
    }

    /**
     * Update the specified Balance in storage.
     *
     * @param  int              $id
     * @param UpdateBalanceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBalanceRequest $request)
    {
        $balance = $this->balanceRepository->findWithoutFail($id);

        if (empty($balance)) {
            Flash::error('Balance not found');

            return redirect(route('balances.index'));
        }

        $balance = $this->balanceRepository->update($request->all(), $id);

        Flash::success('Balance updated successfully.');

        return redirect(route('balances.index'));
    }

    /**
     * Remove the specified Balance from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $balance = $this->balanceRepository->findWithoutFail($id);

        if (empty($balance)) {
            Flash::error('Balance not found');

            return redirect(route('balances.index'));
        }

        $this->balanceRepository->delete($id);

        Flash::success('Balance deleted successfully.');

        return redirect(route('balances.index'));
    }
}
