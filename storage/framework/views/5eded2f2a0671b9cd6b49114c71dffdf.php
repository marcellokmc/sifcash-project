<?php $__env->startSection('title', 'Notifications du Système'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 mb-1">
      <i class="fas fa-bell me-2 text-primary"></i>Notifications du Système
    </h1>
    <p class="text-muted mb-0">Gérez toutes les notifications du système</p>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-success" id="markAllNotificationsRead">
      <i class="fas fa-check-double me-1"></i>Tout marquer comme lues
    </button>
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
      <i class="fas fa-filter me-1"></i>Filtres
    </button>
  </div>
</div>


<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card border-primary">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1">
            <h6 class="text-muted mb-1">Total</h6>
            <h3 class="mb-0"><?php echo e($stats['total']); ?></h3>
          </div>
          <div class="text-primary" style="font-size: 2rem;">
            <i class="fas fa-bell"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-warning">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1">
            <h6 class="text-muted mb-1">Non lues</h6>
            <h3 class="mb-0"><?php echo e($stats['non_lues']); ?></h3>
          </div>
          <div class="text-warning" style="font-size: 2rem;">
            <i class="fas fa-envelope"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $__currentLoopData = $stats['par_type']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="col-md-2">
    <div class="card">
      <div class="card-body text-center">
        <small class="text-muted d-block mb-1"><?php echo e(ucfirst($type)); ?></small>
        <h4 class="mb-0"><?php echo e($count); ?></h4>
      </div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="collapse mb-4" id="filtersCollapse">
  <div class="card">
    <div class="card-body">
      <form method="GET" action="<?php echo e(route('admin.notifications.index')); ?>">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
              <option value="">Tous</option>
              <option value="info" <?php echo e(request('type') === 'info' ? 'selected' : ''); ?>>Info</option>
              <option value="warning" <?php echo e(request('type') === 'warning' ? 'selected' : ''); ?>>Warning</option>
              <option value="alert" <?php echo e(request('type') === 'alert' ? 'selected' : ''); ?>>Alert</option>
              <option value="success" <?php echo e(request('type') === 'success' ? 'selected' : ''); ?>>Success</option>
              <option value="error" <?php echo e(request('type') === 'error' ? 'selected' : ''); ?>>Error</option>
              <option value="email" <?php echo e(request('type') === 'email' ? 'selected' : ''); ?>>Email</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Statut</label>
            <select name="lu" class="form-select">
              <option value="">Tous</option>
              <option value="0" <?php echo e(request('lu') === '0' ? 'selected' : ''); ?>>Non lues</option>
              <option value="1" <?php echo e(request('lu') === '1' ? 'selected' : ''); ?>>Lues</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Action</label>
            <select name="action" class="form-select">
              <option value="">Toutes</option>
              <option value="validation" <?php echo e(request('action') === 'validation' ? 'selected' : ''); ?>>Validation</option>
              <option value="rejet" <?php echo e(request('action') === 'rejet' ? 'selected' : ''); ?>>Rejet</option>
              <option value="approbation" <?php echo e(request('action') === 'approbation' ? 'selected' : ''); ?>>Approbation</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Type d'entité</label>
            <select name="entity_type" class="form-select">
              <option value="">Toutes</option>
              <option value="paiement" <?php echo e(request('entity_type') === 'paiement' ? 'selected' : ''); ?>>Paiement</option>
              <option value="credit" <?php echo e(request('entity_type') === 'credit' ? 'selected' : ''); ?>>Crédit</option>
              <option value="retrait" <?php echo e(request('entity_type') === 'retrait' ? 'selected' : ''); ?>>Retrait</option>
            </select>
          </div>
          <div class="col-md-9">
            <label class="form-label">Recherche</label>
            <input type="text" name="search" class="form-control" placeholder="Rechercher dans titre ou message..." value="<?php echo e(request('search')); ?>">
          </div>
          <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">
              <i class="fas fa-search me-1"></i>Filtrer
            </button>
            <a href="<?php echo e(route('admin.notifications.index')); ?>" class="btn btn-outline-secondary">
              <i class="fas fa-redo"></i>
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php echo $__env->make('components.backoffice.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th style="width: 10%;">Date</th>
            <th style="width: 15%;">Destinataire</th>
            <th style="width: 15%;">Acteur</th>
            <th style="width: 20%;">Titre</th>
            <th style="width: 25%;">Message</th>
            <th style="width: 8%;">Type</th>
            <th style="width: 7%;" class="text-center">Lu</th>
            <th style="width: 10%;" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="<?php echo e(!$n->lu ? 'table-active fw-bold' : ''); ?>">
              <td>
                <small class="text-muted"><?php echo e($n->created_at?->format('d/m/Y')); ?></small><br>
                <small class="text-muted"><?php echo e($n->created_at?->format('H:i')); ?></small>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar-sm me-2">
                    <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                      <?php echo e(substr($n->user?->name ?? '?', 0, 1)); ?>

                    </div>
                  </div>
                  <div>
                    <div class="fw-semibold"><?php echo e($n->user?->name ?? '—'); ?></div>
                    <small class="text-muted"><?php echo e($n->user?->email ?? ''); ?></small>
                  </div>
                </div>
              </td>
              <td>
                <?php if($n->actionByUser): ?>
                  <div class="d-flex align-items-center">
                    <i class="fas fa-user-check text-success me-2"></i>
                    <div>
                      <div><?php echo e($n->actionByUser->name); ?></div>
                      <?php if($n->action): ?>
                        <small class="badge bg-info"><?php echo e(ucfirst($n->action)); ?></small>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td>
                <strong><?php echo e($n->titre); ?></strong>
                <?php if($n->entity_type): ?>
                  <br><small class="badge bg-secondary"><?php echo e(ucfirst($n->entity_type)); ?> #<?php echo e($n->entity_id); ?></small>
                <?php endif; ?>
              </td>
              <td>
                <small><?php echo e(Str::limit($n->message, 80)); ?></small>
              </td>
              <td>
                <?php
                  $badgeClass = match($n->type) {
                    'success' => 'bg-success',
                    'error' => 'bg-danger',
                    'alert' => 'bg-danger',
                    'warning' => 'bg-warning text-dark',
                    'info' => 'bg-info',
                    'email' => 'bg-primary',
                    default => 'bg-secondary'
                  };
                ?>
                <span class="badge <?php echo e($badgeClass); ?>"><?php echo e(ucfirst($n->type)); ?></span>
              </td>
              <td class="text-center">
                <?php if($n->lu): ?>
                  <span class="badge bg-success" data-status>
                    <i class="fas fa-check"></i>
                  </span>
                <?php else: ?>
                  <span class="badge bg-secondary" data-status>
                    <i class="fas fa-times"></i>
                  </span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <?php if(!$n->lu): ?>
                  <button class="btn btn-sm btn-outline-success" data-mark-read data-id="<?php echo e($n->id); ?>" data-url="<?php echo e(route('admin.notifications.mark-read', $n->id)); ?>" title="Marquer comme lue">
                    <i class="fas fa-check"></i>
                  </button>
                <?php else: ?>
                  <button class="btn btn-sm btn-outline-secondary" disabled title="Déjà lue">
                    <i class="fas fa-check-double"></i>
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="8" class="text-center py-5">
                <i class="fas fa-bell-slash text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Aucune notification trouvée</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if(method_exists($items, 'links')): ?>
    <div class="card-footer">
      <?php echo e($items->links()); ?>

    </div>
  <?php endif; ?>
</div>

<style>
.avatar-sm {
  width: 32px;
  height: 32px;
}
.avatar-title {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}
.bg-soft-primary {
  background-color: rgba(13, 110, 253, 0.1);
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Bouton tout marquer comme lues
    const btn = document.getElementById('markAllNotificationsRead');
    if (btn) {
      btn.addEventListener('click', async function(e) {
        e.preventDefault();
        if (!confirm('Voulez-vous vraiment marquer toutes les notifications comme lues ?')) return;
        
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Traitement...';
        
        try {
          const res = await fetch('<?php echo e(route('admin.notifications.mark-all-read')); ?>', {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          if (res.ok) {
            location.reload();
          } else {
            throw new Error('Erreur');
          }
        } catch (err) {
          btn.disabled = false;
          btn.innerHTML = original;
          alert('Une erreur est survenue');
        }
      });
    }

    // Action par ligne: marquer comme lue
    document.querySelectorAll('[data-mark-read]').forEach((button) => {
      button.addEventListener('click', async function(e) {
        e.preventDefault();
        const row = this.closest('tr');
        const status = row.querySelector('[data-status]');
        const url = this.dataset.url;
        const original = this.innerHTML;
        
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
          const res = await fetch(url, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          if (res.ok) {
            if (status) {
              status.className = 'badge bg-success';
              status.innerHTML = '<i class="fas fa-check"></i>';
            }
            row.classList.remove('table-active', 'fw-bold');
            this.className = 'btn btn-sm btn-outline-secondary';
            this.innerHTML = '<i class="fas fa-check-double"></i>';
            this.setAttribute('disabled', 'disabled');
            this.setAttribute('title', 'Déjà lue');
          } else {
            throw new Error('Erreur');
          }
        } catch (err) {
          this.disabled = false;
          this.innerHTML = original;
          alert('Une erreur est survenue');
        }
      });
    });
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/notifications/index.blade.php ENDPATH**/ ?>