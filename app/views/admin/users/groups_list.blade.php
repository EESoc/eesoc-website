@foreach ($groups as $group)
  <li>
    <a href="{{ URL::route('admin.users.index', array('group_id' => $group->id)) }}">
      <span style="padding-left: {{ $level * 20 }}px;"></span>
      {{ $group->name }}
      @if ($group->is_official)
        <span class="badge">{{ $group->users()->count() }}</span>
      @endif
    </a>
  </li>
  @include('admin.users.groups_list', ['groups' => $group->children, 'level' => $level + 1])
@endforeach