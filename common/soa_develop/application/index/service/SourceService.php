<?php
namespace app\index\service;


use app\index\model\branchcompany\CompanyOrder;
use app\index\model\branchcompany\CompanyOrderCustomer;
use app\index\model\branchcompany\CompanyOrderProduct;
use app\index\model\branchcompany\CompanyOrderProductTeam;
use app\index\model\branchcompany\CompanyOrderDiy;
use app\index\model\branchcompany\CompanyOrderProductSource;
use app\index\model\branchcompany\CompanyOrderRelation;
use app\index\model\branchcompany\CompanyOrderProductDiy;
use app\index\model\finance\Receivable;
use app\index\model\finance\ReceivableCustomer;
use app\index\model\finance\Cope;
use app\index\model\product\TeamProduct;
use app\index\model\product\TeamProductAllocation;
use app\index\model\source\TourGuide;
use app\index\model\source\Vehicle;
use app\index\model\source\ScenicSpot;
use app\index\model\source\Visa;
use app\index\model\source\Cruise;
use app\index\model\source\Flight;
use app\index\model\source\Dining;
use app\index\model\source\Hotel;
use app\index\model\source\SingleSource;
use app\index\model\source\OwnExpense;

use think\Model;
use app\common\help\Help;
use think\Hook;

class SourceService{
	
	
	private $_company_order_product;
	private $_company_order_customer;
	private $_company_order_product_team;
	private $_company_order_product_source;
	private $_company_order_product_diy;
	private $_hotel;
	private $_team_product;
	private $_team_product_allocation;
	public function __construct(){
		
		$this->_team_product = new TeamProduct();
		$this->_team_product_allocation = new TeamProductAllocation();
	}

	
	
	//获得资源信息
	public function getSource($params){
		

		$cope = new Cope();
		$receivable = new Receivable();
		$hotel = new Hotel();
		$dining = new Dining();
		$flight = new Flight();
		$cruise = new Cruise();
		$visa = new Visa();
		$scenic_spot = new ScenicSpot();
		$vehicle = new Vehicle();
		$tour_guide = new TourGuide();
		$single_source = new SingleSource();
		$own_expense = new OwnExpense();
	
		if(!empty($params['source_number'])){
			$source_params['source_number'] = $params['source_number'];
			
		}
		if(!empty($params['source_name'])){
			$source_params['source_name'] = $params['source_name'];
				
		}
		if(!empty($params['supplier_name'])){
			$source_params['supplier_name'] = $params['supplier_name'];
		
		}
		$source_params['is_branch_product'] = $params['is_branch_product'];
		$source_params['company_id'] = $params['company_id'];
		$source_params['status'] =1;
	
		if($params['supplier_type_id']==2){
			$hotel = new Hotel();
			if(!empty($params['source_id'])){
				$source_params['hotel_id'] = $params['source_id'];
			}
			
			$source_result = $hotel->getHotel($source_params);
			$source_id = $source_result[0]['hotel_id'];
			$source_name = $source_result[0]['room_name'];
			
		}
		if($params['supplier_type_id']==3){

			$dining = new Dining();
			if(!empty($params['source_id'])){
				$source_params['dining_id'] = $params['source_id'];
			}
			
			$source_result = $dining->getDining($source_params);
			$source_id = $source_result[0]['dining_id'];
			$source_name = $source_result[0]['dining_name'];

		}
		if($params['supplier_type_id']==4){

			$flight = new Flight();
			if(!empty($params['source_id'])){
				$source_params['flight_id'] = $params['source_id'];
			}
		
			$source_result = $flight->getFlight($source_params);
			$source_id = $source_result[0]['flight_id'];
			$source_name = $source_result[0]['flight_number'];
		}
		if($params['supplier_type_id']==5){

			$cruise = new Cruise();
			if(!empty($params['source_id'])){
				$source_params['cruise_id'] = $params['source_id'];
			}
			
			$source_result = $cruise->getCruise($source_params);
			$source_id = $source_result[0]['cruise_id'];
			$source_name = $source_result[0]['cruise_name'];
		}
		if($params['supplier_type_id']==6){

			$visa = new Visa();
			if(!empty($params['source_id'])){
				$source_params['visa_id'] = $params['source_id'];
			}
		
			$source_result = $visa->getVisa($source_params);
			$source_id = $source_result[0]['visa_id'];
			$source_name = $source_result[0]['visa_name'];
		}
		if($params['supplier_type_id']==7){

	
			$scenic_spot = new ScenicSpot();
			if(!empty($params['source_id'])){
				$source_params['scenic_spot_id'] = $params['source_id'];
			}

			$source_result = $scenic_spot->getScenicSpot($source_params);
			$source_id = $source_result[0]['scenic_spot_id'];
			$source_name = $source_result[0]['scenic_spot_name'];
		}
		if($params['supplier_type_id']==8){

			$vehicle = new Vehicle();
			if(!empty($params['source_id'])){
				$source_params['vehicle_id'] = $params['source_id'];
			}
	
			$source_result = $vehicle->getVehicle($source_params);
			$source_id = $source_result[0]['vehicle_id'];
			$source_name = $source_result[0]['vehicle_name'];
		}
		if($params['supplier_type_id']==9){

			$tour_guide = new TourGuide();
			if(!empty($params['source_id'])){
				$source_params['tour_guide_id'] = $params['source_id'];
			}
		
			$source_result = $tour_guide->getTourGuide($source_params);
			$source_id = $source_result[0]['tour_guide_id'];
			$source_name = $source_result[0]['tour_guide_name'];
		}
		if($params['supplier_type_id']==10){

			$single_source = new SingleSource();
			if(!empty($params['source_id'])){
				$source_params['single_source_id'] = $params['source_id'];
			}
		
			$source_result = $single_source->getSingleSource($source_params);
			$source_id = $source_result[0]['single_source_id'];
			$source_name = $source_result[0]['single_source_name'];
		}
		if($params['supplier_type_id']==11){

			$own_expense = new OwnExpense();
			if(!empty($params['source_id'])){
				$source_params['own_expense_id'] = $params['source_id'];
			}
	
			$source_result = $own_expense->getOwnExpense($source_params);
			$source_id = $source_result[0]['own_expense_id'];
			$source_name = $source_result[0]['own_expense_name'];
		}
		if(count($source_result)>0){
			$data = [
				'source_id'=>$source_id,
				'source_number'=>$source_result[0]['source_number'],
				'supplier_type_id'=>$params['supplier_type_id'],	
				'supplier_id'=>$source_result[0]['supplier_id'],
				'supplier_name'=>$source_result[0]['supplier_name'],
				'source_name'=>	$source_name,
				'company_name'=>$source_result[0]['company_name'],
				'normal_price'=>$source_result[0]['normal_price'],
				'normal_settlement_price'=>$source_result[0]['normal_settlement_price'],
				'payment_currency_type'=>$source_result[0]['payment_currency_type'],
				'currency_name'=>$source_result[0]['currency_name'],
				'company_id'=>$source_result[0]['company_id']
			];
		}else{
			$data = [];
		}

		
		
		return $data;
	}
	
	//获得详细资源信息
	public function getSourceInfo($params){
	
	
		$cope = new Cope();
		$receivable = new Receivable();
		$hotel = new Hotel();
		$dining = new Dining();
		$flight = new Flight();
		$cruise = new Cruise();
		$visa = new Visa();
		$scenic_spot = new ScenicSpot();
		$vehicle = new Vehicle();
		$tour_guide = new TourGuide();
		$single_source = new SingleSource();
		$own_expense = new OwnExpense();
	
		if(!empty($params['source_number'])){
			$source_params['source_number'] = $params['source_number'];
				
		}
		if(!empty($params['source_name'])){
			$source_params['source_name'] = $params['source_name'];
	
		}
		if(!empty($params['supplier_name'])){
			$source_params['supplier_name'] = $params['supplier_name'];
	
		}
		$source_params['is_branch_product'] = $params['is_branch_product'];
		$source_params['company_id'] = $params['company_id'];
		$source_params['status'] =1;
		
		
		$data = [];
		if($params['supplier_type_id']==2){
			$hotel = new Hotel();
			if(!empty($params['source_id'])){
				$source_params['hotel_id'] = $params['source_id'];
			}
			
			$source_result = $hotel->getHotel($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['hotel_id'];
				$source_name = $source_result[$i]['room_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],
					'unit'=>$source_result[$i]['unit'],	
				];
				$data[] = $data_params;
			}

				
		}
		if($params['supplier_type_id']==3){
	
			$dining = new Dining();
			if(!empty($params['source_id'])){
				$source_params['dining_id'] = $params['source_id'];
			}
				
			$source_result = $dining->getDining($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['dining_id'];
				$source_name = $source_result[$i]['dining_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],
					'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

	
		}
		if($params['supplier_type_id']==4){
	
			$flight = new Flight();
			if(!empty($params['source_id'])){
				$source_params['flight_id'] = $params['source_id'];
			}
	
			$source_result = $flight->getFlight($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['flight_id'];
				$source_name = $source_result[$i]['flight_number'];
				$data_params = [
						'source_id'=>$source_id,
						'source_number'=>$source_result[$i]['source_number'],
						'supplier_type_id'=>$params['supplier_type_id'],
						'supplier_id'=>$source_result[$i]['supplier_id'],
						'supplier_name'=>$source_result[$i]['supplier_name'],
						'source_name'=>	$source_name,
						'company_name'=>$source_result[$i]['company_name'],
						'normal_price'=>$source_result[$i]['normal_price'],
						'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
						'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
						'currency_name'=>$source_result[$i]['currency_name'],
						'company_id'=>$source_result[$i]['company_id'],
						'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

		}
		if($params['supplier_type_id']==5){
	
			$cruise = new Cruise();
			if(!empty($params['source_id'])){
				$source_params['cruise_id'] = $params['source_id'];
			}
				
			$source_result = $cruise->getCruise($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['cruise_id'];
				$source_name = $source_result[$i]['cruise_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],
					'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

		}
		if($params['supplier_type_id']==6){
	
			$visa = new Visa();
			if(!empty($params['source_id'])){
				$source_params['visa_id'] = $params['source_id'];
			}
	
			$source_result = $visa->getVisa($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['visa_id'];
				$source_name = $source_result[$i]['visa_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],	
					'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

		}
		if($params['supplier_type_id']==7){
	
	
			$scenic_spot = new ScenicSpot();
			if(!empty($params['source_id'])){
				$source_params['scenic_spot_id'] = $params['source_id'];
			}
	
			$source_result = $scenic_spot->getScenicSpot($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['scenic_spot_id'];
				$source_name = $source_result[$i]['scenic_spot_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],			
					'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

		}
		if($params['supplier_type_id']==8){
	
			$vehicle = new Vehicle();
			if(!empty($params['source_id'])){
				$source_params['vehicle_id'] = $params['source_id'];
			}
	
			$source_result = $vehicle->getVehicle($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['vehicle_id'];
				$source_name = $source_result[$i]['vehicle_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],
					'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

		}
		if($params['supplier_type_id']==9){
	
			$tour_guide = new TourGuide();
			if(!empty($params['source_id'])){
				$source_params['tour_guide_id'] = $params['source_id'];
			}
	
			$source_result = $tour_guide->getTourGuide($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['tour_guide_id'];
				$source_name = $source_result[$i]['tour_guide_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],
					'unit'=>$source_result[$i]['unit'],						
				];
				$data[] = $data_params;
			}

		}
		if($params['supplier_type_id']==10){
	
			$single_source = new SingleSource();
			if(!empty($params['source_id'])){
				$source_params['single_source_id'] = $params['source_id'];
			}
	
			$source_result = $single_source->getSingleSource($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['single_source_id'];
				$source_name = $source_result[$i]['single_source_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],
					'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

		}
		if($params['supplier_type_id']==11){
	
			$own_expense = new OwnExpense();
			if(!empty($params['source_id'])){
				$source_params['own_expense_id'] = $params['source_id'];
			}
	
			$source_result = $own_expense->getOwnExpense($source_params);
			for($i=0;$i<count($source_result);$i++){
				$source_id = $source_result[$i]['own_expense_id'];
				$source_name = $source_result[$i]['own_expense_name'];
				$data_params = [
					'source_id'=>$source_id,
					'source_number'=>$source_result[$i]['source_number'],
					'supplier_type_id'=>$params['supplier_type_id'],
					'supplier_id'=>$source_result[$i]['supplier_id'],
					'supplier_name'=>$source_result[$i]['supplier_name'],
					'source_name'=>	$source_name,
					'company_name'=>$source_result[$i]['company_name'],
					'normal_price'=>$source_result[$i]['normal_price'],
					'normal_settlement_price'=>$source_result[$i]['normal_settlement_price'],
					'payment_currency_type'=>$source_result[$i]['payment_currency_type'],
					'currency_name'=>$source_result[$i]['currency_name'],
					'company_id'=>$source_result[$i]['company_id'],			
					'unit'=>$source_result[$i]['unit'],
				];
				$data[] = $data_params;
			}

		}

	
	
		return $data;
	}
}