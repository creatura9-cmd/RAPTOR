```blade
@extends('layouts.app') 
 
@section('content') 
 
<div class="container mx-auto mt-10"> 
 
    <div class="bg-white shadow-lg rounded-lg p-6"> 
 
        <div class="flex justify-between items-center mb-6"> 
 
            <h2 class="text-2xl font-bold text-gray-700"> 
                Listado de Marcas de Moto 
            </h2> 
 
            <a href="{{ route('marca_moto.create') }}" 
               class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition"> 
 
                Nueva Marca 
 
            </a> 
 
        </div> 
 
        @if(session('success')) 
 
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"> 
 
                {{ session('success') }} 
 
            </div> 
 
        @endif 
 
        @if(session('update')) 
 
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4"> 
 
                {{ session('update') }} 
 
            </div> 
 
        @endif 
 
        @if(session('destroy')) 
 
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"> 
 
                {{ session('destroy') }} 
 
            </div> 
 
        @endif 
 
        <table class="min-w-full border border-gray-300"> 
 
            <thead class="bg-gray-200"> 
 
                <tr> 
 
                    <th class="border px-4 py-2"> 
                        ID 
                    </th> 
 
                    <th class="border px-4 py-2"> 
                        Nombre Marca 
                    </th> 
 
                    <th class="border px-4 py-2"> 
                        Acciones 
                    </th> 
 
                </tr> 
 
            </thead> 
 
            <tbody> 
 
                @foreach ($marcas as $marca) 
 
                    <tr class="text-center hover:bg-gray-50"> 
 
                        <td class="border px-4 py-2"> 
                            {{ $marca->id }} 
                        </td> 
 
                        <td class="border px-4 py-2"> 
                            {{ $marca->nombre_marca }} 
                        </td> 
 
                        <td class="border px-4 py-2">
                            <div class="flex items-center justify-center gap-3">

                                <!-- Botón Editar -->
                                <a href="{{ route('marca_moto.edit', $marca->id) }}"
                                   class="text-blue-600 hover:text-blue-900 p-1"
                                   title="Editar">

                                    <svg class="w-5 h-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>

                                    </svg>

                                </a>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('marca_moto.destroy', $marca->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Está seguro de eliminar esta marca?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 p-1"
                                            title="Eliminar">

                                        <svg class="w-5 h-5"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>

                                        </svg>

                                    </button>

                                </form>

                            </div>
                        </td>
 
                    </tr> 
 
                @endforeach 
 
                @if ($marcas->isEmpty()) 
 
                    <tr> 
 
                        <td colspan="3" 
                            class="border px-4 py-6 text-center text-gray-500"> 
 
                            No hay marcas registradas. 
 
                        </td> 
 
                    </tr> 
 
                @endif 
 
            </tbody> 
 
        </table> 
 
    </div> 
 
</div> 
 
@endsection


