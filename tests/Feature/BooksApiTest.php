<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */


    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    
    use RefreshDatabase;
    /** @test */
    function can_get_all_books(){
        $book=Book::factory(4)->create();

        $response=$this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title'=>$book[0]->title
        ])->assertJsonFragment([
            'title'=>$book[1]->title
        ]);
    }

    /** @test */
    function can_get_one_books(){
        $book=Book::factory()->create();

        $response=$this->getJson(route('books.show',$book));

        $response->assertJsonFragment([
            'title'=>$book->title
        ]);
    }

    function can_create_books(){
        $this->postJson(route('books.store'),[])
        ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
            'title'=>'My new book',
        ])->assertJson([
            'title'=>'My new book'
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>'My new book'
        ]);


    }

    /** @test */
    function can_update_books(){
        $book=Book::factory()->create();
        $this->patchJson(route('books.update'),[])
        ->assertJsonValidationErrorFor('title');
        $this->patchJson(route('books.update',$book),[
            'title'=>'Edit book'
        ])->assertJsonFragment([
            'title'=>'Edited book'
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>'Edited book'

        ]);
    }

    /** @test */
    function can_delete_books(){
        $book=Book::factory()->create();// se crea una base de datos es decir se llena el registro porque cada test empieza con data bacia 

        $this->deleteJson(route('books.destroy',$book))
        ->assertNoContent();

        $this->assertDatabaseCount('books',0);
    }


}
