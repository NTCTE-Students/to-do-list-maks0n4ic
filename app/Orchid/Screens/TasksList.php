<?php

namespace App\Orchid\Screens;

use App\Models\Task;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;

class TasksList extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'tasks' => Task::all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Tasks';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Create')
                ->icon('icon-plus')
                ->route('platform.task'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('tasks', [
                TD::make('title')
                    ->render(function (Task $task) {
                        return Link::make($task->title)
                            ->route('platform.task', $task);
                    }),
                TD::make('completed')
                    ->render(function (Task $task) {
                        return $task->completed ? 'Выполнено' : 'Не выполнено';
                    },
                ),
                TD::make('created_at'),
                TD::make('updated_at'),
                TD::make('more')
                    ->render(function (Task $task) {
                        return Link::make('Подробнее')
                            ->route('platform.task.info', $task);
                    }),
            ]),
        ];
    }
}
