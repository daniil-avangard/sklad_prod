<div class="col-lg-4">
    <div class="card">
        <div class="card-body  report-card">
            <div class="row">
                <div class="col d-flex justify-content-between">
                    <p class="text-dark mb-1 fw-semibold">Список групп подразделений</p>
                    <a href="{{ route('user.groups.division.create', $user) }}"
                        class="col-auto align-self-center btn btn-primary">
                        Добавить группу подразделений
                    </a>
                </div>
                <table class="bootstable table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Группа подразделений</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as $group)
                            <tr>
                                <td>{{ $group->id }}</td>
                                <td>{{ $group->name }}</td>
                                <td>
                                    <x-form action="{{ route('user.groups.division.detach', $user) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                                        <button onclick="return confirm('Вы уверены, что хотите удалить эту роль?')"
                                            type="submit" class="btn btn-danger">Удалить</button>
                                    </x-form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!--end col-->
