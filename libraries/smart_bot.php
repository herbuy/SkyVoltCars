<?php


class BotNotificationAfterSelectAnAction{
    public function example(){
        return "";
    }
}
class BotNotificationAfterError extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "i don not know how to respond to that";
    }
}
class BotNotificationForHelp extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "to do x, do y";
    }
}
class BotNotificationForThingsIFound extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "i found these things on the web";
    }
}
class BotNotificationForActionsICanTake extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "what should i do A. Buy it for me B. recommend it to a friend";
    }
}
class BotNotificationForChooseAnOption extends BotNotificationForActionsICanTake{
    public function example(){
        return "Choose an option";
    }
}
class BotNotificationForSlashCommandsUMaySelect extends BotNotificationForActionsICanTake{
    public function example(){
        return "you may choose to A. buy it B. Recommend it";
    }
}
class BotNotificationForThingsICanAsk extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "You many ask for: A. Weather B. Time of departure";
    }
}

class BotNotificationForWelcomingUser extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "welcome user x";
    }
}
class BotNotificationForOnBoardingAUser extends BotNotificationForWelcomingUser{
    public function example(){
        return "welcome. My roles is to help u. for help go here";
    }
}
class BotNotificationForPurposeOfConversation extends BotNotificationForWelcomingUser{
    public function example(){
        return "the purpose of this conversation is to help you get the best flight";
    }
}
class BotNotificationAfterRequestInformation extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "price is 30,000";
    }
}

class BotNotificationToPromptUser extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "give me a word and i will give u its meaning";
    }
}
class BotNotificationWhilePerfomingAction extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "am performing the action";
    }
}
class BotNotificatioWhileConnectingToHumanSupervisor extends BotNotificationWhilePerfomingAction{
    public function example(){
        return "let me connect you to a human supervisor";
    }
}
class BotNotificatioForTypingEvent extends BotNotificationWhilePerfomingAction{
    public function example(){
        return "typing...";
    }
}

class BotNotificationForOptionsAfterUpdate extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "offer received. You may A. accept it B. Reject it";
    }
}
class BotNotificationForNeutralAction extends BotNotificationForActionsICanTake{
    public function example(){
        return "neutral action";
    }
}

class BotNotificationForPrimaryAction extends BotNotificationForActionsICanTake{
    public function example(){
        return "primary action";
    }
}
class BotNotificationForDangerousAction extends BotNotificationForActionsICanTake{
    public function example(){
        return "dangerous action";
    }
}

class BotNotificationForEntireTeam extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "to all: here are some items to check out";
    }
}

class BotNotificationForSelectedTeamMembers extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "to john, alex: here are some items to check out";
    }
}
class BotNotificationForSingleUser extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "to john: here are some items to check out";
    }
}

class BotNotificationToPromptCreateChannel extends BotNotificationToPromptUser{
    public function example(){
        return "create channel";
    }
}
class BotNotificationToPromptAddUsersToChannel extends BotNotificationToPromptUser{
    public function example(){
        return "add users to channel x";
    }
}
class BotNotificationToPromptPostToChannel extends BotNotificationToPromptUser{
    public function example(){
        return "post to channel x";
    }
}
class BotNotificationToPromptMentionUser extends BotNotificationToPromptUser{
    public function example(){
        return "mention the users";
    }
}
class BotNotificationToPromptOpinion extends BotNotificationToPromptUser{
    public function example(){
        return "whats your opinion";
    }
}
class BotNotificationForSomethingHasHappened extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "an offer has been received from person x";
    }
}

class BotNotificationBeforeSigningOut extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "see you later. Bye!";
    }
}

class BotNotificationForLinkToWebContent extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "click here to visit the page";
    }
}
class BotNotificationForLinkToPerformOtherTask extends BotNotificationForLinkToWebContent{
    public function example(){
        return "click here to perform other task";
    }
}
class BotNotificationForLinkToExpandOnContent extends BotNotificationForLinkToWebContent{
    public function example(){
        return "click to see more";
    }
}
class BotNotificationForLinkToPromoteWebContent extends BotNotificationForLinkToWebContent{
    public function example(){
        return "click here to see";
    }
}
class BotNotificationForLinkToProvidePeekIntoContent extends BotNotificationForLinkToWebContent{
    public function example(){
        return "here is a peek into the content. click for more details";
    }
}

class BotNotificationForFileWithAdditionalContent extends BotNotificationAfterSelectAnAction{
    public function example(){
        return "Click to download the full report";
    }
}
class BotNotificationForVideoFileWithAdditionalContent extends BotNotificationForFileWithAdditionalContent{
    public function example(){
        return "Here is a video of the item";
    }
}
class BotNotificationForAudioFileWithAdditionalContent extends BotNotificationForFileWithAdditionalContent{
    public function example(){
        return "here is an audio file for the item";
    }
}
class BotNotificationForImageFileWithAdditionalContent extends BotNotificationForFileWithAdditionalContent{
    public function example(){
        return "here is a photo of the item";
    }
}
class BotNotificationForDocumentWithAdditionalContent extends BotNotificationForFileWithAdditionalContent{
    public function example(){
        return "Here is the full document";
    }
}