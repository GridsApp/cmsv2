@php

    $firstLanguage = collect('languages')[0]['prefix'] ?? 'en';

    $operationSelectionRule = collect($entity['gridRules'])
        ->where('operation', 'selection')
        ->first();
    $operationDeleteRule = collect($entity['gridRules'])
        ->where('operation', 'delete')
        ->first();
    $operationEditRule = collect($entity['gridRules'])
        ->where('operation', 'edit')
        ->first();

@endphp

<div x-data="window.Functions.initTable()" class="twa-table-section ">
    <div class="twa-table-card ">
        <div class="twa-card-header ">
            <h3 class="twa-card-title">
                {{ $entity['title'] }}
            </h3>
            <div class="flex gap-5">




                <template x-if="selected.length > 0">
                    <div class="flex gap-5 items-center">



                        <div x-data="{ showModal: false, handleOpen() { this.showModal = true } }">
                            {!! button("'Delete ('+ selected.length + ')'", 'danger', null, 'button', null, 'handleOpen') !!}


                            @component('components.modal', [
                                'title' => 'Delete',
                                'variable' => 'showModal',
                                'action' => [
                                    'label' => "'Delete'",
                                    'type' => 'danger',
                                    'handler' => 'handleDeleteAll',
                                ],
                            ])
                                <div class="text-[13px] font-medium text-left text-gray-800 p-5">
                                    Are you sure you want to delete records?
                                </div>
                            @endcomponent
                        </div>

                    </div>
                </template>
                <template x-if="selected.length == 0">
                    {!! link_button('Add new Record', route('entity.create', ['slug' => $entity['slug']]), 'primary', 'text-[12px]') !!}
                </template>
            </div>
        </div>
        <div class="twa-card-body">
            @if (count($rows) > 0)
                <div class="">
                    <table class="twa-table table-auto">
                        <thead>
                            <tr>



                                <th class="w-[60px]">
                                    <input x-model="selectedAll" class="checkbox checkbox-sm" @change="handleSelectAll"
                                        type="checkbox">
                                </th>
                                @foreach ($columns as $column)
                                    <th>
                                        {{ $column['label'] }}
                                    </th>
                                @endforeach


                                <th class="w-[60px]">
                                    Actions
                                </th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rows as $row)
                                @php

                                    $i = $row->id;

                                @endphp

                                <tr wire:key="key_{{ $i }}" data-id="{{ $i }}">
                                    <td>
                                        <input
                                            @if (
                                                $operationSelectionRule &&
                                                    compare_values(
                                                        $row->{$operationSelectionRule['condition']['field']},
                                                        $operationSelectionRule['condition']['operand'],
                                                        $operationSelectionRule['condition']['value'])) {{ $operationSelectionRule['action'] }} @endif
                                            class="checkbox  checkbox-row" x-model="selected" type="checkbox"
                                            value="{{ $i }}" @change="handleSelect">
                                    </td>

                                    @foreach ($columns as $column)

                                        @php


                        
                                            if($column['translatable']){
                                                $column['info']['name'] =  $column['info']['name'].'_'.$firstLanguage;
                                            }

                                        @endphp 
     
                                       
                                        <td> {!! (new ($column['type'])($column['info']))->display((array) $row) !!} </td>                                    
                                      



                                
                                    @endforeach



                                    <td @if (
                                        $operationEditRule &&
                                            compare_values(
                                                $row->{$operationEditRule['condition']['field']},
                                                $operationEditRule['condition']['operand'],
                                                $operationEditRule['condition']['value'])) data-operation-disable-edit="true" @endif
                                        @if (
                                            $operationDeleteRule &&
                                                compare_values(
                                                    $row->{$operationDeleteRule['condition']['field']},
                                                    $operationDeleteRule['condition']['operand'],
                                                    $operationDeleteRule['condition']['value'])) data-operation-disable-delete="true" @endif
                                        class="td-actions"
                                        :class="checkTDActionsDisabled('{{ $i }}') ? 'disabled' : ''"
                                        id="td-actions-{{ $i }}" data-target="{{ $i }}">


                                        <a :disabled="checkTDActionsDisabled('{{ $i }}')" href="javascript:;"
                                            class="icon" @click="handleBox(event , '{{ $i }}')">

                                            <i class="fa-regular fa-ellipsis-vertical"></i>
                                        </a>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex justify-center p-7.5 py-9">
                    <img alt="image" class=" max-h-[230px]" src="/images/empty.svg">

                </div>
                <div class="flex flex-col gap-5 lg:gap-7.5">
                    <div class="flex flex-col gap-3 text-center">
                        <h2 class="text-1.5xl font-semibold text-gray-900">
                            New Member Onboarding and Registration
                        </h2>
                        <p class="text-sm text-gray-800">
                            A streamlined process to welcome and integrate new members into the team,
                            <br>
                            ensuring a smooth and efficient start.
                        </p>
                    </div>
                    <div class="flex justify-center mb-5">
                        {!! link_button('Add new Record', route('entity.create', ['slug' => $entity['slug']]), 'primary', 'text-[12px]') !!}
                    </div>
                </div>

            @endif


        </div>


        <div class="pt-5 pb-5 container-fixed">
            {{ $rows->onEachSide(2)->links(data: ['scrollTo' => false]) }}

        </div>
    </div>


    <div :style="actionsActive ? 'position:absolute; right: ' + coordinates[actionsActive]?.x + 'px;top:' + coordinates[
            actionsActive]
        ?.y + 'px' : ''"
        x-show="actionsActive != null" @click.away= "handleClickAway" class="dropdown-content">



        <div x-show="actions.allowEdit" class="dropdown-menu-item">
            <a :href="'/{{ $entity['slug'] }}/update/' + actionsActive" class="dropdown-menu-link">
                <span class="dropdown-menu-icon"><i class="fa-light fa-pen-to-square"></i></span>
                <span class="dropdown-menu-title">Edit Record</span>
            </a>
        </div>
        <div x-show="actions.allowDelete" class="dropdown-menu-item" x-data="{ showModal: false, handleOpen() { this.showModal = true } }"
            @click.away="showModal = false" @click="handleOpen">
            <div class="dropdown-menu-link">
                <span class="dropdown-menu-icon"> <i class="fa-solid fa-trash-can"></i></span>

                <div>

                    <span class="dropdown-menu-title">Delete Record</span>

                    @component('components.modal', [
                        'title' => 'Delete',
                        'variable' => 'showModal',
                        'action' => [
                            'label' => "'Delete'",
                            'type' => 'danger',
                            'handler' => 'handleDelete',
                        ],
                    ])
                        <div class="text-[13px] font-medium text-left text-gray-800 p-5">
                            Are you sure you want to delete records?
                        </div>
                    @endcomponent
                </div>

            </div>
        </div>

    </div>


    <br>



</div>
