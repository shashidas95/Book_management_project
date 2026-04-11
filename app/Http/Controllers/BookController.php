<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Services\Contracts\BookServiceInterface;
use App\Http\Resources\BookResource;
use Exception;

class BookController extends Controller
{
    public function __construct(
        protected BookServiceInterface $bookService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $books = $this->bookService->getAllBooks();

        // Passing transformed collection to the success helper
        return $this->success(BookResource::collection($books));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        try {
            $book = $this->bookService->createBook($request->validated());

            return $this->success(
                new BookResource($book),
                'Book created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $book = $this->bookService->getBookById($id);
            return $this->success(new BookResource($book));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, int $id): JsonResponse
    {
        try {
            $book = $this->bookService->updateBook($id, $request->validated());
            return $this->success(new BookResource($book), 'Book updated successfully');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->bookService->deleteBook($id);
            return $this->success(null, 'Book deleted successfully');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 404);
        }
    }

    /**
     * Borrow a book.
     */
    public function borrow(int $id): JsonResponse
    {
        try {
            $this->bookService->borrowBook($id);
            return $this->success(null, 'Book borrowed successfully');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    /**
     * Return a book.
     */
    public function return(int $id): JsonResponse
    {
        try {
            $this->bookService->returnBook($id);
            return $this->success(null, 'Book returned successfully');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
