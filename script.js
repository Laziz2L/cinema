function confirmDelete(msg) {
    if (msg == 1) msg = "Вы подтверждаете удаление? Это также удалит все сеансы, связанные с фильмом.";
    else if (msg == 2) msg = "Вы подтверждаете удаление? Это также удалит все сеансы, связанные с залом.";
    else if (msg == 4) msg = "Вы подтверждаете удаление? Это также удалит все бронирования по этому сеансу.";
    else msg = "Вы подтверждаете удаление?";

    if (confirm(msg)) {
        return true;
    } else {
        return false;
    }
}
