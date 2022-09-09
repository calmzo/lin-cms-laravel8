<?php

namespace App\Lib\Search;

use App\Models\Article;
use App\Models\User;

class ArticleDocument
{

    /**
     * 设置文档
     *
     * @param Article $article
     * @return \XSDocument
     */
    public function setDocument(Article $article)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($article);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param Article $article
     * @return array
     */
    public function formatDocument(Article $article)
    {
        if (is_array($article->tags) || is_object($article->tags)) {
            $article->tags = json_encode($article->tags);
        }

        $owner = '{}';

        if ($article->owner_id > 0) {
            $owner = $this->handleUser($article->owner_id);
        }

        $category = '{}';

        if ($article->category_id > 0) {
            $category = $this->handleCategory($article->category_id);
        }

        return [
            'id' => $article->id,
            'title' => $article->title,
            'cover' => $article->cover,
            'summary' => $article->summary,
            'tags' => $article->tags,
            'category_id' => $article->category_id,
            'owner_id' => $article->owner_id,
            'create_time' => $article->create_time,
            'view_count' => $article->view_count,
            'like_count' => $article->like_count,
            'comment_count' => $article->comment_count,
            'favorite_count' => $article->favorite_count,
            'category' => $category,
            'owner' => $owner,
        ];
    }

    protected function handleUser($id)
    {
        $user = User::query()->find($id);

        return json_encode([
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ]);
    }

    protected function handleCategory($id)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($id);

        return json_encode([
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

}
