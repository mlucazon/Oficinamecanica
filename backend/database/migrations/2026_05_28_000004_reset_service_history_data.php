<?php

use Illuminate\Database\Migrations\Migration;
use App\Support\ServiceHistoryReset;

return new class extends Migration
{
    public function up(): void
    {
        ServiceHistoryReset::clear();
    }

    public function down(): void
    {
        // This migration intentionally only clears generated service history.
    }
};
