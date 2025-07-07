$filePath = "c:/Razrabotka/Sait HR WindSurf/Front-new/pages/admin/view-application.vue"
$content = Get-Content -Path $filePath -Raw

$blockToRemove = @"

            <!-- Сопроводительное письмо кандидата -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Сопроводительное письмо кандидата</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <p v-if="application.cover_letter">{{ application.cover_letter }}</p>
                  <p v-else class="text-gray-500 italic">Кандидат не оставил сопроводительное письмо</p>
                </div>
              </div>
            </div>
"@

$newContent = $content.Replace($blockToRemove, "")
Set-Content -Path $filePath -Value $newContent -Encoding UTF8
Write-Host "Дублирующийся блок успешно удален"
