<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;

class SubjectController extends ApiController
{
    public function index(): JsonResponse
    {
        $subjects = Subject::orderBy('name')->get();

        return $this->success(SubjectResource::collection($subjects));
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $subject = Subject::create($request->validated());

        return $this->success(new SubjectResource($subject), 'Matéria cadastrada com sucesso.', 201);
    }

    public function show(Subject $subject): JsonResponse
    {
        return $this->success(new SubjectResource($subject));
    }

    public function update(StoreSubjectRequest $request, Subject $subject): JsonResponse
    {
        $subject->update($request->validated());

        return $this->success(new SubjectResource($subject), 'Matéria atualizada com sucesso.');
    }

    public function destroy(Subject $subject): JsonResponse
    {
        if ($subject->books()->exists()) {
            return $this->error('Não é possível excluir: existem livros vinculados a esta matéria.', 422);
        }

        $subject->delete();

        return $this->success(null, 'Matéria excluída com sucesso.');
    }
}
