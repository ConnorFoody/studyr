

#import <UIKit/UIKit.h>
#import "OverlayView.h"
#import "Group.h"

@protocol DraggableViewDelegate <NSObject>

-(void)cardSwipedLeft:(UIView *)card;
-(void)cardSwipedRight:(UIView *)card;

@end

@interface DraggableView : UIView

@property (weak) id <DraggableViewDelegate> delegate;

@property (nonatomic, strong)UIPanGestureRecognizer *panGestureRecognizer;
@property (nonatomic)CGPoint originalPoint;
@property (nonatomic,strong)OverlayView* overlayView;
@property (nonatomic,strong)Group* group;
@property (nonatomic,strong)UILabel* name;

-(void)leftClickAction;
-(void)rightClickAction;

@end
