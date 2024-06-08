<?php

namespace App\Orchid\Screens;

use App\Models\Task;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;

class TaskInfo extends Screen
{
    public $task;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Task $task): iterable
    {
        return [
            'task' => $task,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Task Info';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Удалить')
                ->icon('icon-trash')
                ->confirm('Вы действительно хотите удалить задачу?')
                ->method('remove')
                ->canSee($this->task->exists),
            Button::make('Изменить статус')
                ->icon('icon-check')
                ->method('changeStatus')
                ->canSee($this->task->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('task', [
                Sight::make('title', 'Название'),
                Sight::make('description', 'Описание'),
                Sight::make('completed', 'Статус')->render(function ($task) {
                    return $task->completed ? 'Выполнено' : 'Не выполнено';
                }),
            ])
        ];
    }

    public function remove()
    {
        $this->task->delete();
        return redirect()->route('platform.tasks');
    }

    public function changeStatus()
    {
        $this->task->completed = !$this->task->completed;
        $this->task->save();
    }
}
