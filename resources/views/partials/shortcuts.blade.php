<a href="{{ route('meaning_create_path') }}" type="submit" class="btn btn-success">
  <span class="glyphicon glyphicon-plus-sign"></span>
</a>
<a href="{{ route('meaning_wotd_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip" title="Word of the Day">
  <span class="glyphicon glyphicon-certificate"></span>
</a>
<?php /*
<a href="{{ route('statistics_path2') }}" type="submit" class="btn btn-primary">
  <span class="glyphicon glyphicon-certificate"></span> NEW STATS
</a>
*/ ?>
<a href="{{ route('statistics_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip" title="Statistics">
  <span class="glyphicon glyphicon-stats"></span>
</a>
<a href="{{ route('recent_words_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip" title="Recent words">
  <span class="glyphicon glyphicon-calendar"></span>
</a>
<?php /* At the moment we only want to show backup for the admins */ ?>
@if (Auth::user()->name == 'Gabrielle Tranchet' || Auth::user()->name == 'Daniel Eickhardt')
  <a href="{{ route('backup_show_path') }}" type="submit" class="btn btn-primary" data-toggle="tooltip" title="Backup">
    <span class="glyphicon glyphicon-hdd"></span>
  </a>
@endif