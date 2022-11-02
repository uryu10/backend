<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
   use RefreshDatabase;
    /** @test */
    public function can_get_all_books()
    {
       $books = Book::factory(4)->create();
       $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title 
            ])->assertJsonFragment([
                'title' => $books[1]->title 
            ]);
    }

    /** @test */
    public function can_get_one_book(){
        $book = Book::factory()->create();
        $this->getJson(route('books.show',$book))
             ->assertJsonFragment([
                'title' => $book->title
             ]);
    }

    /** @test */
    public function can_create_books(){
        $this->postJson(route('books.store'),[])
        ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
            'title' => 'My New Book'
        ])->assertJsonFragment([
            'title' => 'My New Book'
        ]);
        $this->assertDatabaseHas('books',[
            'title' => 'My New Book'
        ]);
    }

    /** @test */
    public function can_update_books(){
        $book = Book::factory()->create();

        $this->patchJson(route('books.update',$book),[])
        ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update',$book),[
            'title' => 'Edited title'
        ])->assertJsonFragment([
            'title' => 'Edited title'
        ]);

        $this->assertDatabaseHas('books',[
            'title' => 'Edited title'
        ]);
    }

     /** @test */
     public function can_delete_books(){
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy',$book))
        ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
