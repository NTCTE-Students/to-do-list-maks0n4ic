<?php

namespace App\Orchid\Screens;

use App\Models\Task;
use Orchid\Screen\Action;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;

class TaskEdit extends Screen
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
        return $this->task->exists ? 'Edit task' : 'Create task';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create')
                ->icon('icon-plus')
                ->method('create')
                ->canSee(!$this->task->exists),
            Button::make('Save')
                ->icon('icon-check')
                ->method('save')
                ->canSee($this->task->exists),
            Button::make('Remove')
                ->icon('icon-trash')
                ->confirm('Are you sure you want to delete the task?')
                ->method('remove')
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
            Layout::rows([
                Input::make('task.title')
                    ->title('Name')
                    ->placeholder('Task name')
                    ->help('Enter the name of the task')
                    ->required(),
                Input::make('task.description')
                    ->title('Description')
                    ->placeholder('Task description')
                    ->help('Enter the description of the task')
                    ->required(),
                CheckBox::make('task.completed')
                    ->sendTrueOrFalse()
                    ->title('Completed'),
            ]),
        ];
    }

    public function create(Request $request)
    {
        $task = new Task();
        $task->fill($request->get('task'));
        $task->save();
        return redirect()->route('platform.tasks');
    }

    public function save(Request $request)
    {
        $this->task->fill($request->get('task'));
        $this->task->save();
        return redirect()->route('platform.tasks');
    }
    public function remove()
    {
        $this->task->delete();
    }
}
