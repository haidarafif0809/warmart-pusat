<div class="row">
	<div class="col-sm-2">
		<b>Master Data</b>
		@foreach($permission_master_data as $permissions) 
			<div class="checkbox">
				<label>
				@if(App\PermissionRole::where('role_id',$otoritas->id)->where('permission_id',$permissions->id)->count() == 1) 
					<input checked="" name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@else  
					<input name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@endif
				{{ $permissions->display_name }}

				</label>
			</div> 
		@endforeach	 
	</div>
	<div class="col-sm-2"> 
	</div>
	<div class="col-sm-2"> 
	</div>
</div>

<div class="row">
	<div class="col-sm-2"> 
		<b>Bank</b>
		@foreach($permission_bank as $permissions) 
			<div class="checkbox">
				<label>
				@if(App\PermissionRole::where('role_id',$otoritas->id)->where('permission_id',$permissions->id)->count() == 1) 
					<input checked="" name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@else  
					<input name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@endif
				{{ $permissions->display_name }}

				</label>
			</div> 
		@endforeach
	</div>

	<div class="col-sm-2"> 
		<b>Customer</b>
		@foreach($permission_customer as $permissions) 
			<div class="checkbox">
				<label>
				@if(App\PermissionRole::where('role_id',$otoritas->id)->where('permission_id',$permissions->id)->count() == 1) 			
					<input checked="" name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@else  
					<input name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@endif
				{{ $permissions->display_name }}

				</label>
			</div> 
		@endforeach
	</div>

	<div class="col-sm-2"> 
		<b>Komunitas</b>
		@foreach($permission_komunitas as $permissions) 
			<div class="checkbox">
				<label>
				@if(App\PermissionRole::where('role_id',$otoritas->id)->where('permission_id',$permissions->id)->count() == 1) 
					<input checked="" name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@else  
					<input name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}"> 
				@endif
				{{ $permissions->display_name }}

				</label>
			</div> 
		@endforeach
	</div>

	<div class="col-sm-2">
		<b>Otoritas</b>
		@foreach($permission_otoritas as $permissions) 
			<div class="checkbox">
				<label>
				@if(App\PermissionRole::where('role_id',$otoritas->id)->where('permission_id',$permissions->id)->count() == 1) 
					<input checked="" name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@else  
					<input name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}"> 
				@endif
				{{ $permissions->display_name }}

				</label>
			</div> 
		@endforeach	   
	</div>

	<div class="col-sm-2"> 
		<b>User</b>
		@foreach($permission_user as $permissions) 
			<div class="checkbox">
				<label>
				@if(App\PermissionRole::where('role_id',$otoritas->id)->where('permission_id',$permissions->id)->count() == 1) 
					<input checked="" name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@else  
					<input name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@endif
				{{ $permissions->display_name }}

				</label>
			</div> 
		@endforeach	
	</div>


	<div class="col-sm-2"> 
		<b>Warung</b>
		@foreach($permission_warung as $permissions) 
			<div class="checkbox">
				<label>
				@if(App\PermissionRole::where('role_id',$otoritas->id)->where('permission_id',$permissions->id)->count() == 1) 
					<input checked="" name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@else  
					<input name="{{$permissions->name}}" type=checkbox value="1" id="{{$permissions->name}}">
				@endif
				{{ $permissions->display_name }}

				</label>
			</div> 
		@endforeach
	</div> 
</div>
{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'type'=>'submit','id'=>'btnSubmitPermession']) !!}