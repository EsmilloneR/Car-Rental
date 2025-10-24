<?php

use Livewire\Volt\Component;

new class extends Component {};
?>

<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10 text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">
            About <span class="text-red-600">Drive & Go</span>
        </h1>
        <p class="text-gray-600 dark:text-gray-300 text-lg">
            Learn more about who we are and how we make your car rental experience simple, fast, and reliable.
        </p>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10 space-y-8">
        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Our Story</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                <span class="font-semibold">Drive & Go</span> started at
                <span class="font-semibold">Twayne Garage Car Rental, Bislig City</span> — born out of a vision to
                provide
                hassle-free, affordable, and trustworthy car rental services for locals and travelers alike.
                What began as a small family-run garage has grown into a full-service car rental solution
                with a focus on customer satisfaction, convenience, and trust.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Our Mission</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                Our mission is simple — to make every trip smooth and worry-free.
                Whether you’re commuting around Bislig City or heading out on a long drive,
                we ensure your journey begins with a well-maintained vehicle and friendly service.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Why Choose Us?</h2>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                <li>✅ Clean, well-maintained, and fuel-efficient vehicles</li>
                <li>✅ Affordable rates with no hidden charges</li>
                <li>✅ Fast, easy booking and reliable customer support</li>
                <li>✅ Locally trusted team right here in Bislig City</li>
            </ul>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Our Location</h2>
            <p class="text-gray-700 dark:text-gray-300 mb-6">
                Visit us at <span class="font-semibold">Twayne Garage Car Rental</span>,
                Andres Soriano Avenue, Mangagoy, Bislig City, Philippines 8311.
            </p>

            <!-- Map Embed -->
            <div class="rounded-xl overflow-hidden shadow-md">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1285.8217970684019!2d126.3545703!3d8.1846658!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32fdbb7fb300f80d%3A0x814d0e431e43ed14!2sTwayne%20Garage%20Car%20Rental%20Bislig!5e0!3m2!1sen!2sph!4v1700000000000!5m2!1sen!2sph"
                    width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Contact Us</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                Have questions or need to book a ride?
                Feel free to reach us via email at <span
                    class="font-semibold text-red-600">cloacalkissed14@yahoo.com</span>
                or call us at <span class="font-semibold text-red-600">+63 977 300 5696 / +63 929 304 8310</span>.
            </p>
        </section>
    </div>
</div>
