<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

class UserEnrolledCoursesTest extends TestCase
{
    /** @test */
    public function enrolled_courses_relation_is_defined_correctly()
    {
        $user = new User();

        $relation = $user->enrolledCourses();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('enrollments', $relation->getTable());
    }
}
