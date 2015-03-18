@extends('app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>
				<span class="glyphicon glyphicon-hdd"></span> Backup
			</h2>
		</div>
		<div class="panel-body">

			<div class="panel panel-default">
				<div class="panel-body">

					<p>This is the page where we handle the backups. A backup is a .sql snapshot of the current state of the database. Click the button to perform a backup, and it will appear in the list below where you may download it at your leisure.</p>

					<p>Backups are also generated every night at 00:00, and saved on the server. To increase backup safety, use the backup button regularly.</p>

					<a href="{{ route('backup_path') }}" type="submit" class="btn btn-success">
						<span class="glyphicon glyphicon-save"></span> Create snapshot
					</a>

				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="search_settings_header"><span class="glyphicon glyphicon-info-sign"></span> Recent backups</h3>
				</div>
				<div class="panel-body">
					<div class="panel panel-default">
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr>
										<td>File</td>
										<td>Created by</td>
										<td>Created at</td>
										<td></td>
									</tr>
								</thead>
								<tbody>
									@forelse ($backups as $backup)
										<tr class="vertical_middle">
											<td>{!! link_to_route('download_backup_path', $backup->file, $backup->id) !!}</td>
											<td>{{ $backup->user->name }}</td>
											<td>{{ date("F j, Y, g:i a", strtotime($backup->created_at)) }}</td>
											<td class="text_center">
												{!! Form::open(['route' => ['backup_delete_path', $backup->id], 'method' => 'DELETE', 'id' => 'delete_backup_form_{{ $backup->id }}']) !!}
													<button type="submit" id="delete_backup_btn" class="btn btn-danger">
														<span class="glyphicon glyphicon-remove"></span> Delete
													</button>
												{!! Form::close() !!}
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="4">There seems to be nothing here.</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<a href="{{ route('search_path') }}" type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-search"></span> Goto search
			</a>

			{!! link_to_route('mwdata1_path', 'One') !!}
			{!! link_to_route('mwdata2_path', 'Two') !!}
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(function() 
		{
			$( "#delete_backup_btn" ).on('click', function(e) 
			{
				$(this).button("disable");
				if (confirm("Are you sure you want to delete this snapshot?"))
				{
					$(this).form.submit();
				}
				else 
				{
					$(this).button("enable");
				}
				e.preventDefault();
			});
		});
	</script>
@endsection