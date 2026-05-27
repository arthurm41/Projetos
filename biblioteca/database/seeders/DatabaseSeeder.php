<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Role;
use App\Models\StockEntry;
use App\Models\StockWithdrawal;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $roleAlmoxarife = Role::where('slug', 'almoxarife')->first();
        $roleProfessor  = Role::where('slug', 'professor')->first();

        // Usuários
        $almoxarife = User::firstOrCreate(
            ['email' => 'almoxarife@senai.br'],
            ['name' => 'Almoxarife', 'password' => Hash::make('password')]
        );
        $almoxarife->roles()->syncWithoutDetaching([$roleAlmoxarife->id]);

        $professor = User::firstOrCreate(
            ['email' => 'professor@senai.br'],
            ['name' => 'Professor', 'password' => Hash::make('password')]
        );
        $professor->roles()->syncWithoutDetaching([$roleProfessor->id]);

        // Matérias
        $eletrica = Subject::firstOrCreate(
            ['name' => 'Eletrotécnica'],
            ['description' => 'Fundamentos de instalações elétricas e automação']
        );
        $mecanica = Subject::firstOrCreate(
            ['name' => 'Mecânica Industrial'],
            ['description' => 'Processos de usinagem, soldagem e manutenção mecânica']
        );
        $info = Subject::firstOrCreate(
            ['name' => 'Informática'],
            ['description' => 'Desenvolvimento de software, redes e banco de dados']
        );
        $admin = Subject::firstOrCreate(
            ['name' => 'Administração'],
            ['description' => 'Gestão empresarial, finanças e recursos humanos']
        );

        // Livros
        $livros = [
            [
                'subject_id'    => $eletrica->id,
                'title'         => 'Instalações Elétricas Prediais',
                'isbn'          => '978-85-216-3401-0',
                'author'        => 'Mamede Filho, João',
                'publisher'     => 'LTC',
                'edition'       => '13ª Edição',
                'current_stock' => 45,
                'minimum_stock' => 10,
            ],
            [
                'subject_id'    => $eletrica->id,
                'title'         => 'Automação Industrial',
                'isbn'          => '978-85-7560-237-5',
                'author'        => 'Georgini, Marcelo',
                'publisher'     => 'Érica',
                'edition'       => '4ª Edição',
                'current_stock' => 8,
                'minimum_stock' => 10,
            ],
            [
                'subject_id'    => $mecanica->id,
                'title'         => 'Tecnologia Mecânica',
                'isbn'          => '978-85-216-1748-8',
                'author'        => 'Chiaverini, Vicente',
                'publisher'     => 'McGraw-Hill',
                'edition'       => '2ª Edição',
                'current_stock' => 30,
                'minimum_stock' => 10,
            ],
            [
                'subject_id'    => $info->id,
                'title'         => 'PHP & MySQL: Desenvolvimento Web',
                'isbn'          => '978-85-7522-408-1',
                'author'        => 'Nixon, Robin',
                'publisher'     => 'Alta Books',
                'edition'       => '4ª Edição',
                'current_stock' => 5,
                'minimum_stock' => 10,
            ],
            [
                'subject_id'    => $admin->id,
                'title'         => 'Administração: Teoria e Prática',
                'isbn'          => '978-85-352-5042-0',
                'author'        => 'Chiavenato, Idalberto',
                'publisher'     => 'Elsevier',
                'edition'       => '10ª Edição',
                'current_stock' => 20,
                'minimum_stock' => 10,
            ],
        ];

        foreach ($livros as $dadosLivro) {
            Book::firstOrCreate(['isbn' => $dadosLivro['isbn']], $dadosLivro);
        }

        // Movimentações de exemplo (entrada)
        $livroEletrica = Book::where('isbn', '978-85-216-3401-0')->first();
        if ($livroEletrica && StockEntry::count() === 0) {
            $before = $livroEletrica->current_stock - 50;
            StockEntry::create([
                'book_id'      => $livroEletrica->id,
                'user_id'      => $almoxarife->id,
                'quantity'     => 50,
                'stock_before' => $before < 0 ? 0 : $before,
                'stock_after'  => $livroEletrica->current_stock,
                'notes'        => 'Remessa inicial recebida da editora LTC.',
                'received_at'  => now()->subDays(10),
            ]);
        }

        // Movimentações de exemplo (saída)
        $livroAutomacao = Book::where('isbn', '978-85-7560-237-5')->first();
        if ($livroAutomacao && StockWithdrawal::count() === 0) {
            StockWithdrawal::create([
                'book_id'      => $livroAutomacao->id,
                'user_id'      => $almoxarife->id,
                'quantity'     => 2,
                'stock_before' => 10,
                'stock_after'  => 8,
                'class_group'  => 'Turma ELE-2025-A',
                'reason'       => 'Distribuição para alunos do módulo de automação.',
                'withdrawn_at' => now()->subDays(3),
            ]);
        }

        // Requisição de exemplo (pendente)
        if (\App\Models\BookRequisition::count() === 0 && $livroAutomacao) {
            \App\Models\BookRequisition::create([
                'book_id'      => $livroAutomacao->id,
                'requested_by' => $professor->id,
                'quantity'     => 3,
                'class_group'  => 'Turma ELE-2026-B',
                'reason'       => 'Livros necessários para o módulo de automação industrial.',
                'status'       => 'pending',
            ]);
        }
    }
}
