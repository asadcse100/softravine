@extends(Theme::getThemeNamespace('views.ecommerce.customers.master'))
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="customer-page-title">{{ SeoHelper::getTitle() }}</h2>
        </div>
        <div class="panel-body">
            <div class="section-content">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Product Name') }}</th>
                            <th>{{ __('Purchase key') }}</th>
                            <th class="text-center">{{ __('Times downloaded') }}</th>
                            <th>{{ __('Ordered at') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($orderProducts) > 0)
                            @foreach ($orderProducts as $orderProduct)
                                <tr>
                                    <td>
                                        <img src="{{ RvMedia::getImageUrl($orderProduct->product_image, 'thumb', false, RvMedia::getDefaultImage()) }}" width="50" alt="{{ $orderProduct->product_name }}">
                                    </td>
                                    <td>
                                        {{ $orderProduct->product_name }}
                                        @if ($sku = Arr::get($orderProduct->options, 'sku')) ({{ $sku }}) @endif
                                        @if ($attributes = Arr::get($orderProduct->options, 'attributes'))
                                            <p class="mb-0">
                                                <small>{{ $attributes }}</small>
                                            </p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span>{{ $orderProduct->license_code }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{ $orderProduct->times_downloaded }}</span>
                                    </td>
                                    <td>{{ $orderProduct->created_at->translatedFormat('M d, Y h:m') }}</td>
                                    <td class="text-right">
                                        @if ($orderProduct->product_file_internal_count)
                                            <a class="btn btn-primary mb-2" style="white-space: nowrap" href="{{ route('customer.downloads.product', $orderProduct->id) }}">
                                                <i class="icon icon-download mr-1"></i>&nbsp;
                                                <span>{{ __('Download') }}</span>
                                            </a>
                                        @endif
                                        @if ($orderProduct->product_file_external_count)
                                            <a class="btn btn-info mb-2" style="white-space: nowrap" href="{{ route('customer.downloads.product', [$orderProduct->id, 'external' => true]) }}">
                                                <i class="icon icon-link2"></i>&nbsp;
                                                <span>{{ __('External link downloads') }}</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No digital products!') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {!! $orderProducts->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
