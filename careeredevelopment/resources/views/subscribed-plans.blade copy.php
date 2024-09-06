<div class="row">
    @if($subscriptions->isEmpty())
        <!-- Display Upgrade Button if User has No Subscriptions -->
        <div class="col-4">
            <div class="card p-3">
                <h5 class="card-title">Upgrade Your Plan</h5>
                <div class="card-body">
                    <ul>
                        <li>This is the basic plan.</li>
                        <li>This is the basic plan.</li>
                        <li>This is the basic plan.</li>
                        <li>This is the basic plan.</li>
                        <li>This is the basic plan.</li>
                        <li>This is the basic plan.</li>
                    </ul>
                    <a href="{{ route('upgrade.plan', ['plan_id' => 'basic-plan-id']) }}" class="btn btn-primary w-100">Upgrade to Basic Plan</a>
                    <!-- Replace 'basic-plan-id' with actual plan ID -->
                </div>
            </div>
        </div>
    @else
        <!-- Display User's Subscribed Plans -->
        @foreach($subscriptions as $subscription)
            <div class="col-4">
                <div class="card p-3">
                    <h5 class="card-title">{{ $subscription->name }}</h5>
                    <div class="card-body">
                        <ul>
                            <li>Benefit 1</li>
                            <li>Benefit 2</li>
                            <li>Benefit 3</li>
                            <li>Benefit 4</li>
                            <li>Benefit 5</li>
                            <li>Benefit 6</li>
                        </ul>
                        <a href="{{ route('cancel.subscription', ['subscription_id' => $subscription->id]) }}" class="btn btn-danger w-100">Cancel Subscription</a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Display Upgrade Options if User Has Subscriptions -->
    <div class="col-4">
        <div class="card p-3">
            <h5 class="card-title">Standard Plan</h5>
            <div class="card-body">
                <ul>
                    <li>Benefit A</li>
                    <li>Benefit B</li>
                    <li>Benefit C</li>
                    <li>Benefit D</li>
                    <li>Benefit E</li>
                    <li>Benefit F</li>
                </ul>
                <a href="{{ route('upgrade.plan', ['plan_id' => 'standard-plan-id']) }}" class="btn btn-primary w-100">Upgrade to Standard Plan</a>
                <!-- Replace 'standard-plan-id' with actual plan ID -->
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card p-3">
            <h5 class="card-title">Premium Plan</h5>
            <div class="card-body">
                <ul>
                    <li>Benefit X</li>
                    <li>Benefit Y</li>
                    <li>Benefit Z</li>
                    <li>Benefit 1</li>
                    <li>Benefit 2</li>
                    <li>Benefit 3</li>
                </ul>
                <a href="{{ route('upgrade.plan', ['plan_id' => 'premium-plan-id']) }}" class="btn btn-primary w-100">Upgrade to Premium Plan</a>
                <!-- Replace 'premium-plan-id' with actual plan ID -->
            </div>
        </div>
    </div>
</div>
