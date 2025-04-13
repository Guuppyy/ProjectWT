<?php

namespace project\template;

class MyTemplate
{
    public function view(string $file, array $data = []): string
    {
        return $this->render(
            $this->compileCode($this->includeFiles(file_get_contents($file))),
            $data
        );
    }

    protected function render(string $code, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        eval('?>' . $code);
        return ob_get_clean();
    }

    protected function includeFiles(string $code): string
    {
        preg_match_all('/{% ?include ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $includedContent = file_get_contents($match[1]);
            $code = str_replace($match[0], $includedContent, $code);
        }
        return $code;
    }

    protected function compileCode(string $code): string
    {
        return $this->compileEchos(
            $this->compileLoops(
                $this->compileConditionals($code)
            )
        );
    }

    protected function compileConditionals(string $code): string
    {
        $code = preg_replace('/{%\s*if\s+(.*?)\s*%}/', '<?php if($1): ?>', $code);
        $code = preg_replace('/{%\s*else\s*%}/', '<?php else: ?>', $code);
        $code = preg_replace('/{%\s*endif\s*%}/', '<?php endif; ?>', $code);
        return $code;
    }

    protected function compileLoops(string $code): string
    {
        $code = preg_replace('/{%\s*foreach\s+([^\s]+)\s+as\s+([^\s]+)\s*%}/', '<?php foreach($1 as $2): ?>', $code);
        $code = preg_replace('/{%\s*endforeach\s*%}/', '<?php endforeach; ?>', $code);
        return $code;
    }

    protected function compileEchos(string $code): string
    {
        return preg_replace('/{{\s*(.+?)\s*}}/', '<?= htmlspecialchars($1, ENT_QUOTES, "UTF-8") ?>', $code);
    }
}
