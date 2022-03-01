@extends('route-browser::_layout')

<?php
/**
 * @var \Illuminate\Support\Collection|\DaveJamesMiller\RouteBrowser\RoutePresenter[] $routes
 */
?>

@section('title', 'Route Browser')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="card my-3">
                    <div class="card-header text-white bg-dark">
                        <a href="https://laravel.com/docs/routing" target="_blank" class="float-right text-white" style="line-height: 1.2;">
                            <i class="fas fa-question-circle" aria-hidden="true"></i>
                            Laravel Docs
                        </a>
                        <h6 class="m-0">Routes</h6>
                    </div>
                    <div class="card-body p-0">

                        <form class="form-inline bg-secondary p-3">
                            <div class="form-group mr-2">
                                <label for="method" class="sr-only">Method</label>
                                <select class="custom-select" name="method" id="method">
                                    <option value="">All Methods</option>
                                    @foreach (Illuminate\Routing\Router::$verbs as $verb)
                                        <option value="{{ $verb }}" {{ request('method') === $verb ? 'selected' : '' }}>{{ $verb }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2 w-50">
                                <label for="uri" class="sr-only">URL or Path to match against (may include * wildcard)</label>
                                <input class="form-control form-block w-100" name="uri" id="uri" placeholder="URL or Path to match against (may include * wildcard)" value="{{ request('uri') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Find Routes</button>
                            @if (request('method') || request('uri'))
                                <a href="{{ route('route-browser.list') }}" class="btn btn-dark ml-2">Clear</a>
                            @endif
                        </form>

                        <div class="table-responsive">
                            <table class="table {{ count($routes) ? 'table-hover' : '' }} route-browser-table-sticky m-0 w-100">
                                <thead>
                                    <tr>
                                        <th class="pl-sm-3">Method</th>
                                        <th>Scheme</th>
                                        <th>Domain</th>
                                        <th>Path</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                        <th>Middlewares</th>
                                        <th class="pr-sm-3"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($routes as $route)
                                        <tr class="route-browser-row">
                                            <td class="pl-sm-3">
                                                @if ($route->method())
                                                    {{ $route->method() }}
                                                @else
                                                    <span class="text-muted">&mdash;</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($route->scheme())
                                                    {{ $route->scheme() }}
                                                @else
                                                    <span class="text-muted">&mdash;</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($route->domain())
                                                    {{ $route->domain() }}
                                                @else
                                                    <span class="text-muted">&mdash;</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $route->path() }}
                                            </td>
                                            <td>
                                                @if ($route->name())
                                                    {{ $route->name() }}
                                                @else
                                                    <span class="text-muted">&mdash;</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($view = $route->view())
                                                    <span class="text-muted">View:</span>
                                                    {{ $view }}
                                                @elseif ($route->action()->exists())
                                                    {{ $route->action()->summary() }}
                                                @else
                                                    <span class="text-danger">{{ $route->action()->summary() }} (missing)</span>
                                                @endif
                                            </td>
                                            <td>{{ $route->middleware()->count() }}</td>
                                            <td class="pr-sm-3text-right route-browser-link">
                                                @if ($link = $route->link())
                                                    <a href="{{ $link }}" target="_blank">Visit</a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="route-browser-details-row">
                                            <td class="p-0 bg-white"></td>
                                            <td colspan="7" class="py-0 bg-white">
                                                <div class="route-browser-details">
                                                    <div class="py-3 pr-3">
                                                        @include('route-browser::_route-details')
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <span class="text-danger">
                                                    @if (request('method') || request('uri'))
                                                        No routes found matching the filters.
                                                    @else
                                                        Application doesn't have any routes.
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@stop

@push('footer')

    <div class="modal fade" id="source-code" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Source Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <pre id="source-code-code" class="m-0 p-3"></pre>
                </div>
            </div>
        </div>
    </div>

@endpush
