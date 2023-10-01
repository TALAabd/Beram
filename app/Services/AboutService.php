<?php

namespace App\Services;

use App\Models\About;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AboutService
{

    public function getAll($request)
    {
        App::setlocale($request->lang);
        return About::first();
    }
    public function getPrivacy($request)
    {
        App::setlocale($request->lang);
        return About::first();
    }
    public function getTerms($request)
    {
        App::setlocale($request->lang);
        return About::first();
    }

    public function find($aboutId)
    {
        return About::find($aboutId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $about = About::create($validatedData);

        DB::commit();

        return $about;
    }

    public function update($validatedData, $aboutId)
    {
        $about = About::find($aboutId);

        DB::beginTransaction();

        $about->setTranslation('title', $validatedData['lang'], $validatedData['title']);
        $about->setTranslation('content', $validatedData['lang'], $validatedData['content']);
        $about->save();

        DB::commit();

        return $about;
    }
    public function updatePrivacy($validatedData)
    {
        $about = About::first();

        DB::beginTransaction();

        $about->setTranslation('privacy', $validatedData['lang'], $validatedData['privacy']);
        $about->save();

        DB::commit();

        return $about;
    }
    public function updateTerms($validatedData)
    {
        $about = About::first();

        DB::beginTransaction();

        $about->setTranslation('terms', $validatedData['lang'], $validatedData['terms']);
        $about->save();

        DB::commit();

        return $about;
    }
    
    public function delete($aboutId)
    {
        $about = $this->aboutRepository->find($aboutId);

        DB::beginTransaction();

        $this->aboutRepository->delete($about);

        DB::commit();

        return true;
    }
}
