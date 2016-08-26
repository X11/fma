@if (session('status'))
    <section class="section">
        <div class="container">
            <div class="notification is-primary">
                <button class="delete"></button>
                {{ session('status') }}
            </div>
        </div>
    </section>
@endif
