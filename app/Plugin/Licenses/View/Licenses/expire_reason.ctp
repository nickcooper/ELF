<h2>License Expiration Information</h2>

<?php
    // do we have expirations?
    if (empty($expire_dates['next'])) :
?>

<p>Expiration Date information for this license is incomplete. It's likely this license has not been approved yet.</p>

<?php
    // we have expirations
    else :
?>

<p>License <strong><?php echo $license['License']['license_number']; ?></strong> is expiring on <?php  echo GenLib::dateFormat($expire_dates['dates'][$expire_dates['next']]); ?> because of an <?php echo $expire_dates['next']; ?>.</p>

<?php if ($expire_dates['bypass'] && $is_admin) : ?>
    <p><i class="icon-exclamation-sign red"></i> <strong>Attention:</strong> The expiration date for this license is restricted because the application's bypass validation flag was enabled.</p>
<?php endif; ?>

<h3>Active Expiration Dates</h3>

<table>
    <tbody>
        <tr>
            <th>Expiration Reason</th>
            <th>Expiration Date</th>
        </tr>
        <?php foreach ($expire_dates['dates'] as $reason => $date) : ?>
            <?php if (!$date) : continue; endif; ?>
            <tr>
                <td><?php echo ucwords($reason); ?></td>
                <td><?php echo GenLib::dateFormat($date); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h4>Expiration Date Definitions</h4>

<?php foreach ($expire_dates['dates'] as $reason => $date) : ?>
    <?php if (!$date) : continue; endif; ?>
    <p>
        <strong><?php echo ucwords($reason); ?> Expiration Date:</strong>
        <br /><?php echo $definitions[$reason]; ?>
    </p>
<?php endforeach; ?>

<?php endif; ?>