<?php $__env->startSection('title', 'Crédits'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('components.backoffice.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
  <div>
    <h1 class="h3 mb-0">Gestion des Crédits</h1>
    <p class="text-muted mb-0">Liste des demandes de crédit et leur traitement</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <form method="GET" action="<?php echo e(route('admin.credits.index')); ?>" class="d-flex" role="search">
      <div class="input-group input-group-sm">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="search" name="q" value="<?php echo e(request('q', request('search'))); ?>" class="form-control" placeholder="Rechercher (id, adhérent, téléphone, motif...)" />
        <?php $__currentLoopData = request()->except(['q','search','page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <button class="btn btn-primary" type="submit">OK</button>
      </div>
    </form>
    <div class="btn-group" role="group">
      <a href="<?php echo e(route('admin.credits.index')); ?>" 
         class="btn <?php echo e(!request('statut') && !request('etat') ? 'btn-primary' : 'btn-outline-primary'); ?>">
        <i class="fas fa-list me-1"></i>Tous
      </a>
      <a href="<?php echo e(route('admin.credits.index', ['etat' => 'soumis'])); ?>" 
         class="btn <?php echo e(request('etat') === 'soumis' ? 'btn-warning' : 'btn-outline-warning'); ?>">
        <i class="fas fa-paper-plane me-1"></i>Soumis
        <?php $soumisCount = \App\Models\Credit::where('etat', 'soumis')->count() ?>
        <?php if($soumisCount > 0): ?><span class="badge bg-light text-dark ms-1"><?php echo e($soumisCount); ?></span><?php endif; ?>
      </a>
      <a href="<?php echo e(route('admin.credits.index', ['etat' => 'en_examen'])); ?>" 
         class="btn <?php echo e(request('etat') === 'en_examen' ? 'btn-info' : 'btn-outline-info'); ?>">
        <i class="fas fa-search me-1"></i>En examen
        <?php $examenCount = \App\Models\Credit::where('etat', 'en_examen')->count() ?>
        <?php if($examenCount > 0): ?><span class="badge bg-light text-dark ms-1"><?php echo e($examenCount); ?></span><?php endif; ?>
      </a>
      <a href="<?php echo e(route('admin.credits.index', ['etat' => 'approuve'])); ?>" 
         class="btn <?php echo e(request('etat') === 'approuve' ? 'btn-success' : 'btn-outline-success'); ?>">
        <i class="fas fa-check me-1"></i>Approuvés
      </a>
      <a href="<?php echo e(route('admin.credits.index', ['etat' => 'actif'])); ?>" 
         class="btn <?php echo e(request('etat') === 'actif' ? 'btn-primary' : 'btn-outline-primary'); ?>">
        <i class="fas fa-play me-1"></i>Actifs
      </a>
      
      <!-- Menu filtres -->
      <div class="dropdown">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filtersMenu" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-filter me-1"></i>Filtres
        </button>
        <div class="dropdown-menu dropdown-menu-end p-3 shadow" aria-labelledby="filtersMenu" style="min-width: 420px;" data-bs-auto-close="outside">
          <form method="GET" action="<?php echo e(route('admin.credits.index')); ?>">
            <div class="row g-2">
              <div class="col-md-6">
                <label class="form-label small">Statut</label>
                <select name="statut" class="form-select form-select-sm">
                  <option value="">-- Tous --</option>
                  <?php $__currentLoopData = ['en_attente','approuve','rejete','actif','remboursé']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('statut')===$s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small">État</label>
                <select name="etat" class="form-select form-select-sm">
                  <option value="">-- Tous --</option>
                  <?php $__currentLoopData = ['soumis','en_examen','approuve','contrat','actif','rejete','cloture']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($e); ?>" <?php echo e(request('etat')===$e ? 'selected' : ''); ?>><?php echo e(ucfirst($e)); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Périodicité</label>
                <select name="periodicite" class="form-select form-select-sm">
                  <option value="">-- Toutes --</option>
                  <?php $__currentLoopData = ($periodicites ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p); ?>" <?php echo e(request('periodicite')===$p ? 'selected' : ''); ?>><?php echo e(ucfirst($p)); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Type de crédit</label>
                <select name="type_credit" class="form-select form-select-sm">
                  <option value="">-- Tous --</option>
                  <?php $__currentLoopData = ($types ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(request('type_credit')===$t ? 'selected' : ''); ?>><?php echo e(ucfirst($t)); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label small">Montant min</label>
                <input type="number" class="form-control form-control-sm" name="montant_min" value="<?php echo e(request('montant_min')); ?>" placeholder="Ex: 100000" />
              </div>
              <div class="col-md-6">
                <label class="form-label small">Montant max</label>
                <input type="number" class="form-control form-control-sm" name="montant_max" value="<?php echo e(request('montant_max')); ?>" placeholder="Ex: 1000000" />
              </div>
              <div class="col-md-6">
                <label class="form-label small">Durée min (mois)</label>
                <input type="number" class="form-control form-control-sm" name="duree_min" value="<?php echo e(request('duree_min')); ?>" />
              </div>
              <div class="col-md-6">
                <label class="form-label small">Durée max (mois)</label>
                <input type="number" class="form-control form-control-sm" name="duree_max" value="<?php echo e(request('duree_max')); ?>" />
              </div>
              <div class="col-md-6">
                <label class="form-label small">Date de demande (du)</label>
                <input type="date" class="form-control form-control-sm" name="date_from" value="<?php echo e(request('date_from')); ?>" />
              </div>
              <div class="col-md-6">
                <label class="form-label small">Date de demande (au)</label>
                <input type="date" class="form-control form-control-sm" name="date_to" value="<?php echo e(request('date_to')); ?>" />
              </div>
              <div class="col-12">
                <label class="form-label small">Agence</label>
                <select name="agence_id" class="form-select form-select-sm">
                  <option value="">-- Toutes --</option>
                  <?php $__currentLoopData = ($agences ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($ag->id); ?>" <?php echo e(request('agence_id')==$ag->id ? 'selected' : ''); ?>><?php echo e($ag->nom); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="mt-3 d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search me-1"></i>Appliquer
              </button>
              <a href="<?php echo e(route('admin.credits.index')); ?>" class="btn btn-outline-secondary btn-sm">Réinitialiser</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Adhérent</th>
            <th>Montant demandé</th>
            <th>Montant accordé</th>
            <th>Taux</th>
            <th>Durée</th>
            <th>Périodicité</th>
            <th>Statut</th>
            <th>État</th>
            <th width="200">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><?php echo e($credit->id); ?></td>
              <td><?php echo e($credit->adherent->nom ?? '—'); ?></td>
              <td><?php echo e(number_format((float)$credit->montant_demande, 2, ',', ' ')); ?> FCFA</td>
              <td><?php echo e($credit->montant_accorde ? number_format((float)$credit->montant_accorde, 2, ',', ' ') . ' FCFA' : '—'); ?></td>
              <td><?php echo e((float)$credit->taux); ?>%</td>
              <td><?php echo e($credit->duree); ?></td>
              <td><?php echo e(ucfirst($credit->periodicite)); ?></td>
              <td>
                <?php
                  $statutClass = match($credit->statut) {
                    'en_attente', 'soumis' => 'bg-warning text-dark',
                    'approuve', 'approuvé' => 'bg-success',
                    'rejete', 'rejeté' => 'bg-danger',
                    'actif' => 'bg-primary',
                    default => 'bg-secondary'
                  };
                ?>
                <span class="badge <?php echo e($statutClass); ?>"><?php echo e(ucfirst($credit->statut)); ?></span>
              </td>
              <td>
                <?php
                  $etatClass = match($credit->etat) {
                    'soumis', 'en_examen' => 'bg-warning text-dark',
                    'approuve' => 'bg-success',
                    'contrat' => 'bg-info',
                    'actif' => 'bg-primary',
                    'rejete' => 'bg-danger',
                    default => 'bg-secondary'
                  };
                ?>
                <span class="badge <?php echo e($etatClass); ?>"><?php echo e(ucfirst($credit->etat)); ?></span>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <a href="<?php echo e(route('admin.credits.show', $credit)); ?>" 
                     class="btn btn-sm btn-outline-primary" 
                     title="Voir les détails">
                    <i class="fas fa-eye"></i>
                  </a>
                  
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $credit)): ?>
                    <?php if(in_array($credit->etat, ['soumis', 'en_examen'])): ?>
                      <button type="button"
                              class="btn btn-sm btn-success" 
                              data-bs-toggle="modal" 
                              data-bs-target="#approveModal<?php echo e($credit->id); ?>"
                              title="Approuver">
                        <i class="fas fa-check"></i>
                      </button>
                      <button type="button" 
                              class="btn btn-sm btn-danger" 
                              data-bs-toggle="modal" 
                              data-bs-target="#rejectModal<?php echo e($credit->id); ?>"
                              title="Rejeter">
                        <i class="fas fa-times"></i>
                      </button>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="10" class="text-center py-4">
                <div class="text-center">
                  <i class="fas fa-credit-card text-muted" style="font-size: 3rem;"></i>
                  <h5 class="mt-3 text-muted">Aucun crédit trouvé</h5>
                  <p class="text-muted">Les demandes de crédit apparaîtront ici.</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if(method_exists($items, 'links')): ?>
    <div class="card-footer"><?php echo e($items->withQueryString()->links()); ?></div>
  <?php endif; ?>
</div>

<!-- Modales d'approbation et de rejet -->
<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $credit)): ?>
    <?php if(in_array($credit->etat, ['soumis', 'en_examen'])): ?>
      <!-- Modal Approbation -->
      <div class="modal fade" id="approveModal<?php echo e($credit->id); ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Approuver le crédit #<?php echo e($credit->id); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.credits.approve', $credit)); ?>">
              <?php echo csrf_field(); ?>
              <div class="modal-body">
                <div class="alert alert-info">
                  <strong>Adhérent:</strong> <?php echo e($credit->adherent->nom ?? 'N/A'); ?><br>
                  <strong>Montant demandé:</strong> <?php echo e(number_format($credit->montant_demande, 0, ',', ' ')); ?> FCFA<br>
                  <strong>Durée:</strong> <?php echo e($credit->duree); ?> mois
                </div>
                
                <div class="mb-3">
                  <label for="montant_accorde<?php echo e($credit->id); ?>" class="form-label">Montant accordé <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="number" 
                           class="form-control" 
                           id="montant_accorde<?php echo e($credit->id); ?>"
                           name="montant_accorde" 
                           value="<?php echo e($credit->montant_demande); ?>" 
                           min="0" 
                           step="1000" 
                           required>
                    <span class="input-group-text">FCFA</span>
                  </div>
                </div>
                
                <div class="mb-3">
                  <label for="taux<?php echo e($credit->id); ?>" class="form-label">Taux d'intérêt (%)</label>
                  <div class="input-group">
                    <input type="number" 
                           class="form-control" 
                           id="taux<?php echo e($credit->id); ?>"
                           name="taux" 
                           value="<?php echo e($credit->taux); ?>" 
                           min="0" 
                           max="50" 
                           step="0.1">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-check me-1"></i>Approuver le crédit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Rejet -->
      <div class="modal fade" id="rejectModal<?php echo e($credit->id); ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Rejeter le crédit #<?php echo e($credit->id); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.credits.reject', $credit)); ?>">
              <?php echo csrf_field(); ?>
              <div class="modal-body">
                <div class="alert alert-warning">
                  <strong>Attention:</strong> Cette action rejettera définitivement la demande de crédit.
                </div>
                
                <div class="alert alert-info">
                  <strong>Adhérent:</strong> <?php echo e($credit->adherent->nom ?? 'N/A'); ?><br>
                  <strong>Montant demandé:</strong> <?php echo e(number_format($credit->montant_demande, 0, ',', ' ')); ?> FCFA
                </div>
                
                <div class="mb-3">
                  <label for="motif_rejet<?php echo e($credit->id); ?>" class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                  <textarea class="form-control" 
                            id="motif_rejet<?php echo e($credit->id); ?>"
                            name="motif_rejet" 
                            rows="4" 
                            placeholder="Expliquez les raisons du rejet..."
                            required></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">
                  <i class="fas fa-times me-1"></i>Rejeter le crédit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/credits/index.blade.php ENDPATH**/ ?>