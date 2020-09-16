<a href="{{ route('meaning_create_path') }}" type="submit" class="btn btn-success">
    <span class="glyphicon glyphicon-plus-sign"></span>
</a>
<a href="{{ route('meaning_wotd_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip"
   title="Word of the Day">
    <span class="glyphicon glyphicon-certificate"></span>
</a>
<a href="{{ route('statistics_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip" title="Statistics">
    <span class="glyphicon glyphicon-stats"></span>
</a>
<a href="{{ route('recent_words_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip"
   title="Recent words">
    <span class="glyphicon glyphicon-calendar"></span>
</a>
@if (Auth::user()->is_admin)
    <a href="{{ route('backup_show_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip"
       title="Backup">
        <span class="glyphicon glyphicon-hdd"></span>
    </a>
@endif